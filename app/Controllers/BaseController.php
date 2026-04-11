<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['navigation', 'audit_tooltip'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    /**
     * Override del metodo view() per passare automaticamente il route
     * Es: FornitoriController => route = 'fornitori'
     */
    protected function view(string $name, array $data = [], array $options = []): string
    {
        $controllerName = strtolower(str_replace('Controller', '', class_basename($this::class)));
        $data['route'] = $data['route'] ?? $controllerName;

        return view($name, $data, $options);
    }

    /**
     * Restituisce l'URL a cui tornare dopo un'azione (salvataggio, eliminazione, ecc.).
     *
     * Il meccanismo è volutamente semplice e si basa su due soli casi:
     *
     *  - Richiesta GET (form di creazione o modifica):
     *      getPost('backTo') è vuoto, quindi si usa previous_url() — la pagina
     *      da cui l'utente è arrivato. Questo valore viene passato alla view
     *      e inserito in un <input type="hidden" name="backTo">.
     *
     *  - Richiesta POST (salvataggio o eliminazione):
     *      il form ha portato con sé il campo nascosto "backTo", quindi
     *      getPost('backTo') restituisce esattamente la pagina di provenienza.
     *
     * Se nessuno dei due è disponibile si usa il $fallback fornito.
     *
     * @param string $fallback URL di default (es. url_to('fornitori_index'))
     * @return string L'URL a cui redirigere l'utente
     */
    protected function getBackTo(string $fallback): string
    {
        return $this->request->getPost('backTo')
            ?: previous_url()
            ?: $fallback;
    }
}
