<?php

namespace App\Middleware;

use App\Domain\Models\TwoFactorAuthModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class AdminAuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TwoFactorAuthModel $twoFactorModel,
        private ResponseFactoryInterface $responseFactory
    ) {}

    public function process(Request $request, RequestHandler $handler): Response
    {
        $userId = SessionManager::get('user_id');
        $authStatus = SessionManager::get('is_auth');
        $role = strtolower(trim(SessionManager::get('user_role')));

        // TODO: Retrieve the user's authentication status and role from the session.
        // Check authenticated
        //       If not authenticated, redirect to the login page.
        if(!$authStatus) {
            FlashMessage::error('Please log in');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $loginUrl = $routeParser->urlFor('auth.login');

            $response = $this->responseFactory->createResponse(302);
            return $response->withHeader('Location', $loginUrl);
        }


        //       If authenticated but not an admin, redirect to the user dashboard
        //       with an access denied message.

        //       If both checks pass, allow the request to proceed.
        //
        //       Use the same redirect pattern as AuthMiddleware (RouteParser + responseFactory).

        // After authenticated, check user is an admin
        if ($role !== 'admin') {
            FlashMessage::error('Access denied');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('user.dashboard');

            $response = $this->responseFactory->createResponse(302);
            return $response->withHeader('Location', $url);
        }


        // After verifying the user is an admin, check if 2FA is enabled:
        if (!$this->twoFactorModel->isEnabled($userId)) {
            FlashMessage::warning('Admin accounts require Two-Factor Authentication. Please enable 2FA to continue.');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $setupUrl = $routeParser->urlFor('2fa.setup');

            $response = $this->responseFactory->createResponse(302);
            return $response->withHeader('Location', $setupUrl);
        }

        return $handler->handle($request);
    }
}
