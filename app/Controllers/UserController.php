<?php

namespace App\Controllers;

use App\Domain\Models\ItemModel;
use App\Domain\Models\TransactionModel;
use App\Domain\Models\UserModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;

class UserController extends BaseController
{
    public function __construct(
        Container $container,
        private UserModel $userModel,
        private ItemModel $itemModel,
        private TransactionModel $transactionModel
    ) {
        parent::__construct($container);
    }

    public function dashboard(Request $resquest, Response $response, array $args): Response
    {
        $userId = SessionManager::get('user_id');

        $totalUserItems = $this->itemModel->countItemsByUser($userId);
        $totalCartItems = $this->transactionModel->countCartItems($userId);


        $title = 'User Dashboard';
        $data = [
            'title' => $title,
            'username' => SessionManager::get('username', 'User'),
            'totalUserItems' => $totalUserItems,
            'totalCartItems' => $totalCartItems
        ];

        // TODO: Our user dashboard is in UserController rather than AuthController as indicated in Lab-13
        // 1. Query the database to check whether the current user has
        //    2FA enabled.
        // 2. Render 'dashboard.php', passing the 2FA status so the
        //    view can display the correct toggle button.

        return $this->render($response, 'user/userDashboard.php', $data);
    }
    public function profile(Request $request, Response $response): Response
    {
        // Get user ID from session
        $userId = SessionManager::get('user_id');

        if (!$userId) {
            FlashMessage::error('You must be logged in to view your profile.');
            return $this->redirect($request, $response, 'auth.login');
        }

        // Get user profile data
        $profile = $this->userModel->getUserProfile($userId);

        if (!$profile) {
            FlashMessage::error('Profile not found.');
            return $this->redirect($request, $response, 'user.dashboard');
        }

        $data = [
            'title' => 'My Profile',
            'username' => SessionManager::get('username', $profile['username'] ?? 'User'),
            'profile' => $profile
        ];

        return $this->render($response, 'profile/profileView.php', $data);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request, Response $response): Response
    {
        $userId = SessionManager::get('user_id');

        if (!$userId) {
            FlashMessage::error('You must be logged in to update your profile.');
            return $this->redirect($request, $response, 'auth.login');
        }

        $params = $request->getParsedBody();
        $username = trim($params['username'] ?? '');
        $email = trim($params['email'] ?? '');

        // Validation
        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        // Check if email exists for another user
        if (!empty($email)) {
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser && $existingUser['user_id'] != $userId) {
                $errors[] = 'Email already exists';
            }
        }
        //check if username exists for another user
        if (!empty($username)) {
            $existingUser = $this->userModel->findByUsername($username);
            if ($existingUser && $existingUser['user_id'] != $userId) {
                $errors[] = 'Username already exists';
            }
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                FlashMessage::error($error);
            }
            return $this->redirect($request, $response, 'profile.index');
        }
        // Update user profile
        $updated = $this->userModel->updateUserProfile($userId, [
            'username' => $username,
            'email' => $email
        ]);
        if ($updated) {
            // Update session data
            $user = SessionManager::get('user');
            if ($user) {
                $user['username'] = $username;
                $user['email'] = $email;
                SessionManager::set('user', $user);
            }
            SessionManager::set('username', $username);

            FlashMessage::success('Profile updated successfully!');
        } else {
            FlashMessage::error('Failed to update profile.');
        }

        return $this->redirect($request, $response, 'profile.index');
    }
}
