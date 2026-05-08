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
    public function __construct(Container $container, private UserModel $userModel,private TwoFactorAuthModel $twoFactorModel,)
    {
        parent::__construct($container);
    }

    public function register(Request $request, Response $response, array $args): Response
    {
        $data['data'] = [
            'title' => 'Register | Create a new Account'
        ];

        $account_info = SessionManager::get('account_info');

        // Check if there are previously submitted user details
        if ($account_info !== null) {
            $data['data']['account_info'] = $account_info;

            //! IMPORTANT: We do NOT remove it here anymore.
            //* We remove it in the View or after a SUCCESSFUL save to keep it during errors.
            // SessionManager::remove('account_info');
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
            $errors[] = 'Username is required';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
        }

        if (empty($confirm_password)) {
            $errors[] = 'Password confirmation is required';
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if ($this->userModel->emailExists($email)) {
            $errors[] = 'Email already exist';
        }

        if ($this->userModel->usernameExists($username)) {
            $errors[] = 'Username already exist';
        }

        if (strlen($password) < 8 || !preg_match('/\d/', $password)) { // or preg_match('/[0-9]/', $password)
            $errors[] = 'Password must be at least 8 characters and contain at least one number';
        }

        if ($password !== $confirm_password) {
            $errors[] = 'Password and confirmation must match';
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
            FlashMessage::success('Account created succeefully');
            return $this->redirect($request, $response, 'auth.login');
        } else {
            SessionManager::set('account_info', $data);
            FlashMessage::error('Failed to create an account. Please try again');
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

        if ($identifier === '' || $password === '') {
            FlashMessage::error('Invalid credentials. Please Try Again');
            return $this->redirect($request, $response, 'auth.login');
        }

        $user = $this->userModel->verifyCredentials($identifier, $password);

        // dd($user);
        if ($user === null) {
            FlashMessage::error('Invalid credentials. Please Try Again');
            return $this->redirect($request, $response, 'auth.login');
        }

        SessionManager::set('user', [
            'id' => $user['id'],
            'email' => $user['email'],
            'full_name' => $user['first_name'] . ' ' . $user['last_name'],
            'role' => $user['role'],
            'is_auth' => true,
        ]);
        // TODO:
        // 1. Query the database to check whether the user has 2FA enabled.
        $twoFAEnabled=$this->twoFactorModel->isEnabled($user['user_id']);
        // 2. Store the result in the session as 'requires_2fa'.
        SessionManager::set('requires_2fa',$twoFAEnabled);
        // 3. Set 'two_factor_verified' in the session: if the user does not
        //    have 2FA enabled, mark it as already verified so they are not
        //    prompted. If they do have 2FA, mark it as not yet verified.
        SessionManager::set('2fa_verified', !$twoFAEnabled);


         FlashMessage::success("Welcome back, {$user['first_name']}!");

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

        FlashMessage::success('You have been successfully logged out');

        return $this->redirect($request, $response, 'auth.login');
    }
    public function dashboard(Request $request, Response $response): Response
    {
        // TODO:
        // 1. Query the database to check whether the current user has
        //    2FA enabled.
        $userId = SessionManager::get('user_id');
        $twoFAEnabled=$this->twoFactorModel->isEnabled($userId);
        // 2. Render 'dashboard.php', passing the 2FA status so the
        //    view can display the correct toggle button.
        return$this->render($response, 'dashboard.php', [
            'title'           => 'Dashboard',
            'twoFactorEnabled' => $twoFAEnabled,
        ]);
    }
}
