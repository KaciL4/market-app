<?php

namespace App\Controllers;


use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Domain\Models\UserModel;
use DI\Container;
use App\Helpers\ViewHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function __construct(Container $container, private UserModel $userModel)
    {
        parent::__construct($container);
    }

    // GET/LOGIN
    //We don't need to load the header and the footer since they are already loaded by the views!
    public function showLogin(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/auth/login.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    // POST/LOGIN
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            FlashMessage::error("Login Failed");
            return $response
                ->withHeader('Location', APP_BASE_URL . '/auth/login?error=invalid_credentials')
                ->withStatus(302);
        }

        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }

        // $_SESSION['user_id'] = $user['user_id'];
        // $_SESSION['username'] = $user['username'];
        // $_SESSION['role'] = $user['role'];

        SessionManager::set('user_id', $user['user_id']);
        SessionManager::set('username', $user['username']);
        SessionManager::set('role', $user['role']);

        // Redirect based on the role
        if (strtolower($user['role']) === 'admin') {
            return $response
                ->withHeader('Location', APP_BASE_URL . '/admin/dashboard')
                ->withStatus(302);
        }

        return $response
            ->withHeader('Location', APP_BASE_URL . '/')
            ->withStatus(302);
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
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => 'user'
        ];
        $create = $this->userModel->createUser($user_data);

        if ($create > 0) {
            SessionManager::remove('account_info');
            FlashMessage::success('Account created succeefully');
            return $this->redirect($request, $response, 'auth.login');
        } else {
            SessionManager::set('account_info', $data);
            FlashMessage::success('Failed to create an account. Please try again');
            return $this->redirect($request, $response, 'auth.register');
        }
    }
}
