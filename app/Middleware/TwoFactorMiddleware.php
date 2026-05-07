<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Domain\Models\TwoFactorAuthModel;
use App\Helpers\SessionManager;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteContext;

class TwoFactorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TwoFactorAuthModel $twoFactorModel,
        private ResponseFactoryInterface $responseFactory
    ) {}

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Get the logged-in user from the session
        $user = SessionManager::get('user');

        // If user is not authenticated, continue normally
        if (!$user || empty($user['is_auth'])) {
            return $handler->handle($request);
        }

        // Get user ID from session
        $userId = $user['id'];

        // Check if the user has 2FA enabled
        $twoFactorEnabled = $this->twoFactorModel->isEnabled($userId);

        // Check if 2FA has already been verified this session
        $twoFactorVerified = SessionManager::get('2fa_verified');

        // If 2FA is enabled but not verified, redirect to verification page
        if ($twoFactorEnabled && !$twoFactorVerified) {

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();

            $verifyUrl = $routeParser->urlFor('2fa.verify');

            $response = $this->responseFactory->createResponse(302);

            return $response->withHeader('Location', $verifyUrl);
        }

        // Continue request
        return $handler->handle($request);
    }
}
