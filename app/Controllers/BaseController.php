<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\UserModel;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
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
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // --- LOGIKA UPDATE SESSION OTOMATIS ---
        if (session()->get('is_logged')) {
            $userModel = new UserModel();
            $user = $userModel->find(session()->get('user_id'));

            if ($user) {
                // Update nilai token di session dengan data terbaru dari DB
                session()->set('token_balance', $user->token_balance);

                // Kamu juga bisa update data lain jika perlu (misal: username)
                session()->set('username', $user->username);
            } else {
                // Jika user tidak ditemukan di DB (misal dihapus admin), paksa logout
                session()->destroy();
            }
        }
        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }
}
