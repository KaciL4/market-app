<?php

namespace App\Controllers;


use  App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Domain\Models\UserModel;
use App\Helpers\ViewHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    private UserModel $userModel;
    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
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

    // GET /register
    //We don't need to load the header and the footer since they are already loaded by the views!
    public function showRegister(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/auth/register.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    // POST /register
    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $email = trim($data['email'] ?? '');
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $confirmPassword = trim($data['confirm_password'] ?? '');

        if ($password !== $confirmPassword) {
            return $response
                ->withHeader('Location', APP_BASE_URL . '/auth/register?error=password_mismatch')
                ->withStatus(302);
        }

        if ($this->userModel->emailExists($email)) {
            return $response
                ->withHeader('Location', APP_BASE_URL . '/auth/register?error=email_exists')
                ->withStatus(302);
        }

        $this->userModel->createUser([
            'email' => $email,
            'username' => $username,
            'password' => $password
        ]);

        return $response
            ->withHeader('Location', APP_BASE_URL . '/auth/login')
            ->withStatus(302);
    }
}
