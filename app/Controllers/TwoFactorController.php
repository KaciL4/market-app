<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Models\TrustedDeviceModel;
use App\Domain\Models\TwoFactorAuthModel;
use App\Domain\Models\UserModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\BaconQrCodeProvider;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function DI\string;

class TwoFactorController extends BaseController
{
    public function __construct(
        ContainerInterface $container,
        private TwoFactorAuthModel $twoFactorModel,
        private UserModel $userModel,
        private TrustedDeviceModel $trustedDeviceModel
    ) {
        parent::__construct($container);
    }

    public function showSetup(Request $request, Response $response): Response
    {
        // TODO:
        // 1. Query the database to check if the user already has
        //    2FA enabled. If so, set a flash error and redirect
        //    to the dashboard.
        $userId = SessionManager::get('user_id');
        $userEmail = SessionManager::get('user_email');

        if ($this->twoFactorModel->isEnabled($userId)) {
            FlashMessage::error("User already has enabled 2FA.");

            $role = SessionManager::get('user_role');

            if ($role === 'admin') {
                return $this->redirect($request, $response, 'admin.dashboard');
            }

            return $this->redirect($request, $response, 'user.dashboard');
        }

        // 2. Create a QR code provider and a TwoFactorAuth instance.
        $qrProvider = new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg');
        // 3. Generate a new TOTP secret.
        $tfa = new TwoFactorAuth($qrProvider, 'Marketplace');
        $secret = $tfa->createSecret();
        // 4. Store the secret in the session temporarily (not in the
        //    database yet, since the user has not verified the code).
        SessionManager::set('2fa_setup_secret', $secret);
        // 5. Generate a QR code data URI for the authenticator app.
        $qrCodeDataUri = $tfa->getQRCodeImageAsDataUri($userEmail, $secret);
        $data = [
            'title' => 'Enabled 2FA',
            'qrCodeDataUri' => $qrCodeDataUri,
            'secret' => $secret
        ];
        // 6. Render the setup view, passing the QR code and the secret.
        return $this->render($response, 'auth/2fa-setup.php', $data);
    }

    public function verifyAndEnable(Request $request, Response $response): Response
    {
        // TODO:
        // 1. Retrieve the setup secret from the session. If missing,
        //    the setup session has expired: redirect to the setup page.
        $userId = SessionManager::get('user_id');
        $userEmail = SessionManager::get('user_email');

        $secret = SessionManager::get('2fa_setup_secret');
        if (!$secret) {
            FlashMessage::error('Setup session expired. Please start again.');
            return $this->redirect($request, $response, '2fa.setup');
        }

        $data = $request->getParsedBody();
        $code = trim((string)($data['code'] ?? ''));
        // 2. Verify the submitted code against the session secret.
        $qrProvider = new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg');
        $tfa = new TwoFactorAuth($qrProvider, 'DrinkShop');
        $valid = $tfa->verifyCode($secret, $code);
        // 3. If the code is invalid, regenerate the QR code and
        //    re-render the setup page with an error message.
        if (!$valid) {
            FlashMessage::error('Invalid verification code. Please try again.');
            $qrCodeDataUrl = $tfa->getQRCodeImageAsDataUri($userEmail, $secret);
            return $this->render($response, 'auth/2fa-setup.php', $data);
        }

        // 4. If the code is valid, save the secret to the database
        //    and enable 2FA for the user.
        $this->twoFactorModel->create($userId, $secret);
        $this->twoFactorModel->enable($userId);
        // 5. Remove the setup secret from the session.
        SessionManager::remove('2fa_setup_secret');
        // 6. Redirect to the dashboard with a success flash message.
        FlashMessage::success('Success 2FA.');
        $role = SessionManager::get('user_role');

        if ($role === 'admin') {
            return $this->redirect($request, $response, 'admin.dashboard');
        }

        return $this->redirect($request, $response, 'user.dashboard');
    }

    public function showVerify(Request $request, Response $response): Response
    {
        // TODO: Render the 2FA verification view.
        return $this->render($response, 'auth/2fa-verify.php', [
            'title' => '2FA Verification'
        ]);
    }

    public function verify(Request $request, Response $response): Response
    {
        // TODO:
        // 1. Retrieve the user's stored TOTP secret from the database.
        $userId = SessionManager::get('user_id');
        $userEmail = SessionManager::get('user_email');

        $secret = $this->twoFactorModel->getSecret($userId);

        // 2. Verify the submitted code against the stored secret.
        $data = $request->getParsedBody();
        $code = trim((string)($data['code'] ?? ''));
        $qrProvider = new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg');
        $tfa = new TwoFactorAuth($qrProvider, 'DrinkShop');
        $valid = $tfa->verifyCode($secret, $code);
        // 3. If the code is invalid, increment a failed attempts counter
        //    in the session. After 5 failed attempts, destroy the session
        //    and redirect to the login page. Otherwise, re-render the
        //    verification page with an error.
        if (!$valid) {
            $attempts = ((int) SessionManager::get('2fa_attempts') ?? 0) + 1;
            SessionManager::set('2fa_attempts', $attempts);

            if ($attempts >= 5) {
                SessionManager::destroy();
                return $this->redirect($request, $response, 'login');
            }
            return $this->render($response, 'auth/2fa-verify.php', [
                'title' => '2FA Verification'
            ]);
        }
        // 4. If the code is valid, mark 2FA as verified in the session.
        SessionManager::set('2fa_verified', true);

        $trustDevice = $data['trust_device'] ?? false;

        if ($trustDevice) {
            // TODO: Generate a secure random device token.
            $deviceToken = bin2hex(random_bytes(32));

            //       Calculate the expiration date (30 days from now).
            $expireAt = (new \DateTime('+30 days'))->format('Y-m-d H:i:s');

            //       Build a device info array with the device name, user agent,
            //       IP address, and expiration date.
            //       Save the trusted device to the database.
            $deviceInfo = [
                'device_name' => $this->getDeviceName($request),
                'user_agent' => $request->getHeaderLine('User-Agent'),
                'ip_address' => $this->getClientIp($request),
                'expire_at' => $expireAt
            ];

            $this->trustedDeviceModel->create($userId, $deviceToken, $deviceInfo);

            //       Set a secure cookie named 'trusted_device' with the token,
            //       configured with httponly and samesite flags.
            setcookie('trusted_device', $deviceToken, [
                'expires' => time() + (60 * 60 * 24 * 30), // 30 days
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }

        // 5. Clear the attempts counter.
        SessionManager::remove('2fa_attempts');
        // 6. Regenerate the session ID for security.
        session_regenerate_id(true);
        // 7. Redirect to the dashboard.
        $role = SessionManager::get('user_role');

        if ($role === 'admin') {
            return $this->redirect($request, $response, 'admin.dashboard');
        }

        return $this->redirect($request, $response, 'user.dashboard');
    }

    public function disable(Request $request, Response $response): Response
    {
        // TODO:
        // 1. Retrieve the submitted password from the form data.
        $userId = SessionManager::get('user_id');
        $data = $request->getParsedBody();
        $password = (string) ($data['password'] ?? '');
        // 2. Look up the user in the database and use password_verify()
        //    to confirm the password is correct. If invalid, re-render
        //    the disable page with an error.
        $user = $this->userModel->findById($userId);
        if (!$user || !password_verify($password, $user['password'])) {
            FlashMessage::error('Incorrect password. Two-factor authentication was not disabled.');
            return $this->render($response, 'auth/2fa-disable.php', [
                'title' => 'Disable 2FA'
            ]);
        }
        // 3. Disable 2FA in the database for this user.
        $this->twoFactorModel->disable($userId);

        SessionManager::remove('2fa_verified');
        // 4. Redirect to the dashboard with a success flash message.
        FlashMessage::success('Two-factor authentication has been disabled.');

        $role = SessionManager::get('user_role');

        if ($role === 'admin') {
            return $this->redirect($request, $response, 'admin.dashboard');
        }

        return $this->redirect($request, $response, 'user.dashboard');
    }

    public function showDisable(Request $request, Response $response): Response
    {
        // TODO: Render the disable confirmation view.
        return $this->render($response, 'auth/2fa-disable.php', [
            'title' => 'Disable 2FA'
        ]);
    }

    private function getDeviceName(Request $request): string
    {
        $userAgent = $request->getHeaderLine('User-Agent');

        if (stripos($userAgent, 'Windows') !== false) return 'Windows PC';
        if (stripos($userAgent, 'Mac') !== false) return 'Mac';
        if (stripos($userAgent, 'iPhone') !== false) return 'iPhone';
        if (stripos($userAgent, 'Android') !== false) return 'Android';
        if (stripos($userAgent, 'Linux') !== false) return 'Linux PC';

        return 'Unknown Device';
    }

    private function getClientIp(Request $request): string
    {
        $serverParams = $request->getServerParams();

        if (!empty($serverParams['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $serverParams['HTTP_X_FORWARDED_FOR']);
            return trim($ipList[0]);
        }

        return $serverParams['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
