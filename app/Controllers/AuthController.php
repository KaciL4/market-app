<?php

namespace App\Controllers;
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
    public function showLogin(Request $request, Response $response,): Response
    {
        ob_start();
        ViewHelper::loadHeader('Login');
        require __DIR__.'/../Views/auth/login.php';
        ViewHelper::loadFooter();
        $html=ob_get_clean();
        $response->getBody()->write($html);
        return $response;

    }
    // POST/LOGIN
    public function login(Request $request, Response $response): Response{
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $user = $this->userModel->findByEmail($email);
        
        if(!$user || !$this->userModel->verifyPassword($password, $user['password'])){
            return $response
            ->withHeader('Location','/auth/login?error=invalid_credentials')
            ->withStatus(302);
        }
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        // Redirect based on the role
        if ($user['role'] === 'admin') {
            return $response
                ->withHeader('Location', '/admin/dashboard')
                ->withStatus(302);
        }
        return $response
            ->withHeader('Location','/')
            ->withStatus(302);

    }
    // GET /register
    public function showRegister(Request $request, Response $response): Response{
        ob_start();
        ViewHelper::loadHeader('Register');
        require __DIR__.'/../Views/auth/register.php';
        ViewHelper::loadFooter();
        $html=ob_get_clean();
        $response->getBody()->write($html);
        return $response;

    }
    // POST /register
    public function register(Request $request, Response $response): Response{
        $data = $request->getParsedBody();
        $email = trim($data['email'] ?? '');
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $confirmPassword = trim($data['confirm_password'] ?? '');

        if ($password !== $confirmPassword) {
            return $response
                ->withHeader('Location', '/register?error=password_mismatch')
                ->withStatus(302);
        }
        if($this->userModel->emailExists($email)){
            return $response
                ->withHeader('Location', '/register?error=email_exists')
                ->withStatus(302);
        }
        $this->userModel->createUser([
            'email' => $email,
            'username' => $username,
            'password' => $password
        ]);
        return $response
        ->withHeader('Location','/register')
        ->withStatus(302);
    }
}
