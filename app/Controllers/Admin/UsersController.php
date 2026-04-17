<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Validation\ValidationRules;

class UsersController extends BaseController
{
    public function index()
    {
        $users = auth()->getProvider();

        $usersList = $users
            ->withGroups()
            ->withPermissions()
            ->findAll();

        $data = [
            'title'     => 'Gestione Utenti',
            'usersList' => $usersList,
            // $route viene derivato automaticamente da BaseController::view():
            // Admin\UsersController → "admin/users"
        ];

        return $this->view('admin/users/index', $data);
    }

    public function show($id)
    {
        $users = auth()->getProvider();
        $user  = $users
            ->withGroups()
            ->withPermissions()
            ->find($id);

        if (!$user) {
            return redirect()->to(url_to('users_index'))->with('error', 'Utente non trovato.');
        }

        $backTo = $this->getBackTo(url_to('users_index'));
        $data = [
            'title'   => 'Dettagli Utente: ' . esc($user->username),
            'mode'    => 'view',
            'user'    => $user,
            'backTo'  => $backTo,
            'form'    => [
                'action'     => '',
                'method'     => 'get',
                'spoof'      => null,
                'submitText' => '',
                'readonly'   => true,
            ],
            'allGroups'      => config('AuthGroups')->groups,
            'allPermissions' => config('AuthGroups')->permissions,
        ];
        return $this->view('admin/users/form', $data);
    }

    public function create()
    {
        $backTo = $this->getBackTo(url_to('users_index'));
        $data = [
            'title'  => 'Crea Nuovo Utente',
            'mode'   => 'create',
            'user'   => null,
            'backTo' => $backTo,
            'form'   => [
                'action'     => url_to('users_salva'),
                'method'     => 'post',
                'spoof'      => null,
                'submitText' => 'Salva',
                'readonly'   => false,
            ],
            'allGroups'      => config('AuthGroups')->groups,
            'allPermissions' => config('AuthGroups')->permissions,
        ];
        return $this->view('admin/users/form', $data);
    }

    public function edit($id)
    {
        $users = auth()->getProvider();
        $user  = $users
            ->withGroups()
            ->withPermissions()
            ->find($id);

        if (!$user) {
            return redirect()->to(url_to('users_index'))->with('error', 'Utente non trovato.');
        }

        $backTo = $this->getBackTo(url_to('users_index'));
        return $this->view('admin/users/form', [
            'title'   => 'Modifica Utente: ' . esc($user->username),
            'mode'    => 'edit',
            'user'    => $user,
            'backTo'  => $backTo,
            'form'    => [
                // url_to('users_aggiorna', $id) genera /admin/users/{id} (la rotta PUT ha il placeholder (:num)).
                // Il form invia POST con spoof _method=PUT, che CI4 instradia su put('(:num)') → update()
                'action'     => url_to('users_aggiorna', $id),
                'method'     => 'POST',
                'spoof'      => 'PUT',
                'submitText' => 'Aggiorna',
                'readonly'   => false,
            ],
            'allGroups'      => config('AuthGroups')->groups,
            'allPermissions' => config('AuthGroups')->permissions,
        ]);
    }

    /**
     * store() — crea un nuovo utente da backoffice (POST /utenti/).
     *
     * Shield tiene separati i dati base (username → tabella `users`) dalle
     * credenziali di accesso (email + password hashata → tabella `auth_identities`).
     * Assegnando email e password sull'entità prima del save(), Shield provvede
     * a creare entrambi i record in automatico.
     */
    public function store()
    {
        $users = auth()->getProvider();
        $post  = $this->request->getPost();

        // ------------------------------------------------------------------
        // Passo 1 — Validazione.
        // Verifichiamo formato e unicità dello username prima di toccare il DB.
        // La regola `strong_password` è registrata da Shield (già usata in
        // changePassword). Per l'email lasciamo che Shield restituisca l'errore
        // se risulta duplicata in auth_identities (evita una query ridondante).
        // ------------------------------------------------------------------
        $rules = [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]|strong_password',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // ------------------------------------------------------------------
        // Passo 2 — Creazione entità User.
        // Lo username va nel costruttore; email e password si assegnano
        // tramite i setter dell'entità, che segnalano internamente a Shield
        // che l'identità email_password va creata insieme all'utente.
        // ------------------------------------------------------------------
        $user           = new User(['username' => $post['username']]);
        $user->email    = $post['email'];
        $user->password = $post['password']; // Shield lo hasha con bcrypt prima del salvataggio

        // ------------------------------------------------------------------
        // Passo 3 — Salvataggio via Shield UserModel.
        // save() scrive sia in `users` che in `auth_identities` in modo atomico.
        // Se fallisce (es. email già presente) restituisce false e popola errors().
        // ------------------------------------------------------------------
        if (!$users->save($user)) {
            return redirect()->back()->withInput()
                ->with('errors', $users->errors());
        }

        // ------------------------------------------------------------------
        // Passo 4 — Recupero utente appena creato.
        // Dopo il save() abbiamo solo l'ID auto-generato; ricarichiamo l'entità
        // completa perché addGroup() e addPermission() operano sull'oggetto idratato.
        // ------------------------------------------------------------------
        $newUser = $users->find($users->getInsertID());

        // ------------------------------------------------------------------
        // Passo 5 — Attivazione immediata.
        // Nel flusso di registrazione normale Shield richiede la verifica email.
        // Da backoffice l'admin già conosce l'utente: activate() marca l'identità
        // come verificata direttamente sul DB, saltando l'invio del link.
        // ------------------------------------------------------------------
        $newUser->activate();

        // ------------------------------------------------------------------
        // Passo 6 — Assegnazione gruppi.
        // Su un nuovo utente non serve la DELETE preventiva (non ha ancora gruppi),
        // ma usiamo lo stesso approccio di update() per simmetria.
        // ------------------------------------------------------------------
        $selectedGroups = $post['groups'] ?? [];
        foreach ($selectedGroups as $group) {
            try {
                $newUser->addGroup($group);
            } catch (DatabaseException $e) {
                log_message('error', "Errore nell'aggiungere utente al gruppo $group: " . $e->getMessage());
            }
        }

        // ------------------------------------------------------------------
        // Passo 7 — Assegnazione permessi utente-level.
        // I permessi individuali vivono in `auth_permissions_users`.
        // addPermission() di Shield gestisce l'inserimento; su un nuovo utente
        // non serve la delete preventiva perché non ha permessi preesistenti.
        // ------------------------------------------------------------------
        $selectedPermissions = $post['permissions'] ?? [];
        foreach ($selectedPermissions as $permission) {
            try {
                $newUser->addPermission($permission);
            } catch (DatabaseException $e) {
                log_message('error', "Errore nell'aggiungere permesso $permission all'utente: " . $e->getMessage());
            }
        }

        return redirect()->to(
            $this->getBackTo(url_to('users_index'))
        )->with('success', 'Utente creato con successo.');
    }

    /**
     * update() — salva le modifiche a un utente esistente (PUT /utenti/:id).
     * Riceve l'ID dall'URL tramite method spoofing dal form di modifica.
     */
    public function update($id)
    {
        $users = auth()->getProvider();
        $post  = $this->request->getPost();

        $user = $users->find($id);
        if (!$user) {
            return redirect()->to(url_to('users_index'))->with('error', 'Utente non trovato.');
        }

        $user->username = $post['username'];
        $user->email    = $post['email'];

        if (!$users->save($user)) {
            return redirect()->back()->withInput()
                ->with('error', implode(', ', $users->errors()));
        }

        // Gestione gruppi: elimina tutti i gruppi attuali e riassegna quelli selezionati nel form.
        // Si opera direttamente su DB perché Shield non espone un metodo "setGroups" atomico.
        $db = db_connect();
        $db->table('auth_groups_users')->where('user_id', $id)->delete();
        $selectedGroups = $this->request->getPost('groups') ?? [];
        foreach ($selectedGroups as $group) {
            try {
                $user->addGroup($group);
            } catch (DatabaseException $e) {
                log_message('error', "Errore nell'aggiungere utente al gruppo $group: " . $e->getMessage());
            }
        }

        // Gestione permessi utente-level: stessa logica dei gruppi.
        // I permessi individuali vivono in `auth_permissions_users`; azzeriamo e
        // riassegnamo così un permesso deselezionato nel form viene effettivamente rimosso.
        $db->table('auth_permissions_users')->where('user_id', $id)->delete();
        $selectedPermissions = $this->request->getPost('permissions') ?? [];
        foreach ($selectedPermissions as $permission) {
            try {
                $user->addPermission($permission);
            } catch (DatabaseException $e) {
                log_message('error', "Errore nell'aggiungere permesso $permission all'utente $id: " . $e->getMessage());
            }
        }

        return redirect()->to(
            $this->getBackTo(url_to('users_index'))
        )->with('success', 'Utente aggiornato con successo.');
    }

    public function delete($id)
    {
        $users = auth()->getProvider();
        $user  = $users->find($id);

        if (!$user) {
            return redirect()->to(url_to('users_index'))->with('error', 'Utente non trovato.');
        }

        try {
            $users->delete($id);
        } catch (DatabaseException) {
            return redirect()->to(url_to('users_index'))
                ->with('error', 'Errore nell\'eliminazione utente.');
        }

        return redirect()->to(url_to('users_index'))
            ->with('success', 'Utente eliminato con successo.');
    }

    public function changePassword()
    {
        $user = auth()->user();
        log_message('info', 'Request method: ' . $this->request->getMethod());
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'current_password' => [
                    'label'  => 'Password attuale',
                    'rules'  => 'required|checkCurrentPassword',
                ],
                'new_password'     => [
                    'label' => 'Nuova password',
                    'rules' => 'required|min_length[8]|strong_password',
                ],
                'new_password_confirm' => [
                    'label' => 'Conferma nuova password',
                    'rules' => 'required|matches[new_password]',
                ],
            ];
            log_message('info', 'Validazione con regole: ' . print_r($rules, true));
            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Aggiornamento password
            $user->password = $this->request->getPost('new_password');
            log_message('info', 'Nuovo oggetto user: ' . print_r($user, true));
            $userModel = auth()->getProvider();
            $userModel->save($user);

            return redirect()->back()->with('success', 'Password aggiornata con successo.');
        }

        return $this->view('admin/users/changePassword');
    }

    public function approva($id)
    {
        $users      = auth()->getProvider();
        $user       = $users->withGroups()->withPermissions()->find($id);
        if (!$user) {
            return redirect()->to(url_to('users_index'))->with('error', 'Utente non trovato.');
        }

        if (!in_array('pending', $user->getGroups())) {
            return redirect()->to(url_to('users_index'))
                ->with('error', 'L\'utente non è in stato pending.');
        }

        try {
            $user->removeGroup('pending');
            $user->addGroup('user');

            // ==== Invio email di notifica approvazione ====
            $admin = setting('SiteConfig.adminEmail');
            $email = \Config\Services::email();
            $email->setFrom($admin, 'MeTe Licenze Admin');
            $email->setTo($user->email);
            $email->setSubject('Account Approvato');
            $content = "
                <p>Ciao <strong>" . esc($user->username) . "</strong>,</p>
                <p>Il tuo account è stato approvato. Ora puoi effettuare il login.</p>
                <p><a href='" . setting('SiteConfig.siteURL') . "/login' class='button'>Accedi al gestionale</a></p>
            ";
            $message = view('emails/layout', [
                'title'   => 'Account approvato su MeTe Licenze',
                'content' => $content,
            ]);
            $email->setMessage($message);
            $email->setMailType('html');
            if (!$email->send()) {
                log_message('error', "Errore nell'invio della mail di approvazione a {$user->email}: " . $email->printDebugger(['headers', 'subject', 'body']));
            }
        } catch (DatabaseException $e) {
            log_message('error', "Errore nell'approvare utente $id: " . $e->getMessage());
            return redirect()->to(url_to('users_index'))
                ->with('error', 'Errore nell\'approvazione utente.');
        }

        return redirect()->to(url_to('users_index'))
            ->with('success', 'Utente approvato con successo.');
    }
}
