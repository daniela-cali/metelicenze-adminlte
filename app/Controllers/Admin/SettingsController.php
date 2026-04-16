<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class SettingsController extends BaseController
{
    /**
     * index() — mostra il form con i valori attuali delle impostazioni.
     *
     * setting('SiteConfig.chiave') legge dal DB se il valore è stato salvato,
     * altrimenti torna al default dichiarato in app/Config/SiteConfig.php.
     * Non tocchi mai SiteConfig.php per cambiare un'impostazione: usi il form.
     */
    public function index()
    {
        $data = [
            'title'    => 'Impostazioni sito',
            'settings' => [
                'siteName'      => setting('SiteConfig.siteName'),
                'siteTheme'     => setting('SiteConfig.siteTheme'),
                'adminEmail'    => setting('SiteConfig.adminEmail'),
                'logoPath'      => setting('SiteConfig.logoPath'),
                'siteURL'       => setting('SiteConfig.siteURL'),
                'enableMultiDB' => setting('SiteConfig.enableMultiDB'),
            ],
        ];

        return $this->view('admin/settings_form', $data);
    }

    /**
     * save() — salva le impostazioni nel database (POST /admin/settings/save).
     *
     * service('settings')->set('NomeConfig.chiave', valore) scrive una riga
     * nella tabella `settings`. Da questo momento quella chiave viene letta
     * dal DB invece che da SiteConfig.php.
     */
    public function save()
    {
        $post = $this->request->getPost();
        $s    = service('settings');

        $s->set('SiteConfig.siteName',      $post['siteName']      ?? '');
        $s->set('SiteConfig.siteTheme',     $post['siteTheme']     ?? '');
        $s->set('SiteConfig.adminEmail',    $post['adminEmail']    ?? '');
        $s->set('SiteConfig.siteURL',       $post['siteURL']       ?? '');
        // Il checkbox non invia nulla se non è spuntato, quindi se non c'è nel POST è false
        $s->set('SiteConfig.enableMultiDB', !empty($post['enableMultiDB']));

        // Gestione upload logo.
        // Se l'utente non seleziona nessun file, getFile() esiste ma isValid() è false:
        // in quel caso saltiamo l'aggiornamento e teniamo il percorso già salvato.
        $logo = $this->request->getFile('logo');
        if ($logo !== null && $logo->isValid() && ! $logo->hasMoved()) {

            // Accettiamo solo immagini per evitare upload di file arbitrari.
            $tipiPermessi = ['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'];
            if (! in_array($logo->getMimeType(), $tipiPermessi)) {
                return redirect()->back()->withInput()
                    ->with('error', 'Il logo deve essere un\'immagine (PNG, JPG, GIF, WEBP, SVG).');
            }

            // Manteniamo il nome originale del file; move() lo sposta in public/assets/images/.
            // Se esiste già un file con lo stesso nome viene sovrascritto.
            $nomefile = $logo->getName();
            $logo->move(FCPATH . 'assets/images', $nomefile, true);

            // Salviamo il percorso relativo alla root pubblica, uguale al formato
            // già usato in SiteConfig.php (es. /assets/images/logo.png).
            $s->set('SiteConfig.logoPath', '/assets/images/' . $nomefile);
        }

        return redirect()->to('/admin/settings')->with('success', 'Impostazioni salvate correttamente!');
    }
}
