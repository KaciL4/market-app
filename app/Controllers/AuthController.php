<?php

namespace App\Controllers;


use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Domain\Models\UserModel;
use App\Domain\Models\TwoFactorAuthModel;
use DI\Container;
use App\Helpers\ViewHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function __construct(Container $container, private UserModel $userModel, private TwoFactorAuthModel $twoFactorModel,)
    {
        parent::__construct($container);
    }

    public function register(Request $request, Response $response, array $args): Response
    {

        $data['data'] = [
            'title' => 'Register Page'
        ];

        if (isset($_SESSION['account_info'])) {
            $data['data']['account_info'] = $_SESSION['account_info'];
            SessionManager::remove('account_info');
        }
        return $this->render($response, 'auth/register.php', $data);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        // TODO: Extract the submitted form fields from the request
        $data = $request->getParsedBody();
        $errors = [];

        $email = trim($data['email'] ?? '');
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? ''; //* Password and confirmation can conatin space so no trim()
        $confirm_password = $data['confirm_password'] ?? '';

        if (empty($username)) {
            $errors[] = trans('flash.username_required');
        }

        if (empty($email)) {
            $errors[] = trans('flash.email_required');
        }

        if (empty($password)) {
            $errors[] = trans('flash.password_required');
        }

        if (empty($confirm_password)) {
            $errors[] = trans('flash.password_confirmation_required');
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = trans('flash.invalid_email');
        }

        if ($this->userModel->emailExists($email)) {
            $errors[] = trans('flash.email_exists');
        }

        if ($this->userModel->usernameExists($username)) {
            $errors[] = trans('flash.username_exists');
        }

        if (strlen($password) < 8 || !preg_match('/\d/', $password)) { // or preg_match('/[0-9]/', $password)
            $errors[] = trans('flash.password_requirements');
        }

        if ($password !== $confirm_password) {
            $errors[] = trans('flash.passwords_must_match');
        }

        if (!empty($errors)) {
            SessionManager::set('account_info', $data);
            FlashMessage::error($errors[0]);
            return $this->redirect($request, $response, 'auth.register');
        }

        // if (!empty($errors)) {
        //     foreach ($errors as $error) {
        //         FlashMessage::error($error);
        //     }
        //     return $this->redirect($request, $response, 'auth.register');
        // }

        $user_data = [
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'role' => 'user'
        ];
        $create = $this->userModel->createUser($user_data);

        if ($create > 0) {
            SessionManager::remove('account_info');
            FlashMessage::success(trans('flash.account_created'));
            return $this->redirect($request, $response, 'auth.login');
        } else {
            SessionManager::set('account_info', $data);
            FlashMessage::error(trans('flash.account_create_failed'));
            return $this->redirect($request, $response, 'auth.register');
        }
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        $data = [
            'title' => 'Login'
        ];

        return $this->render($response, 'auth/login.php', $data);
    }

    /**
     * Process login form submission (POST request).
     */
    public function authenticate(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $identifier = trim($data['identifier'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            FlashMessage::error(trans('flash.fill_all_fields'));
            return $this->redirect($request, $response, 'auth.login');
        }

        $user = $this->userModel->verifyCredentials($identifier, $password);

        // dd($user);
        if (!$user) {
            FlashMessage::error(trans('flash.invalid_login'));
            return $this->redirect($request, $response, 'auth.login');
        }
        //store user_id for checkout access
        $userId = $user['user_id'] ?? null;

        if (!$userId) {
            FlashMessage::error(trans('flash.user_id_not_found'));
            return $this->redirect($request, $response, 'auth.login');
        }

        SessionManager::set('user_id', $user['user_id']);
        SessionManager::set('user_email', $user['email']);
        SessionManager::set('username', $user['username']);
        SessionManager::set('user_role', strtolower($user['role']));
        SessionManager::set('is_auth', true);

        SessionManager::set('user', [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role' => strtolower($user['role']),
            'is_auth' => true,
        ]);

        // TODO:
        // 1. Query the database to check whether the user has 2FA enabled.
        $twoFAEnabled = $this->twoFactorModel->isEnabled($user['user_id']);

        // 2. Store the result in the session as 'requires_2fa'.
        SessionManager::set('requires_2fa', $twoFAEnabled);

        // 3. Set 'two_factor_verified' in the session: if the user does not
        //    have 2FA enabled, mark it as already verified so they are not
        //    prompted. If they do have 2FA, mark it as not yet verified.
        SessionManager::set('2fa_verified', !$twoFAEnabled);

        FlashMessage::success(trans('flash.welcome_back') . ' ' . $user['username'] . '!');

        if ($user['role'] === 'admin') {
            return $this->redirect($request, $response, 'admin.dashboard');
        }

        return $this->redirect($request, $response, 'user.dashboard');
    }

    /**
     * Logout the current user (GET request).
     */
    public function logout(Request $request, Response $response, array $args): Response
    {
        SessionManager::destroy();

        FlashMessage::success(trans('flash.logout_success'));

        return $this->redirect($request, $response, 'auth.login');
    }

    // public function dashboard(Request $request, Response $response): Response
    // {
    //     // TODO:
    //     // 1. Query the database to check whether the current user has
    //     //    2FA enabled.
    //     $userId = SessionManager::get('user_id');
    //     $twoFAEnabled = $this->twoFactorModel->isEnabled($userId);
    //     // 2. Render 'dashboard.php', passing the 2FA status so the
    //     //    view can display the correct toggle button.
    //     return $this->render($response, 'dashboard.php', [
    //         'title'           => 'Dashboard',
    //         'twoFactorEnabled' => $twoFAEnabled,
    //     ]);
    // }
}
