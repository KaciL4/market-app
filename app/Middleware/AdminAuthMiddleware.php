<?php

namespace App\Middleware;

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
    public function __construct(private ResponseFactoryInterface $responseFactory)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $is_authenticated = SessionManager::get('is_authenticated');
        $role = strtolower(trim(SessionManager::get('user_role')));

         // TODO: Retrieve the user's authentication status and role from the session.
        //       If not authenticated, redirect to the login page.
        //       If authenticated but not an admin, redirect to the user dashboard
        //       with an access denied message.
        //       If both checks pass, allow the request to proceed.
        //
        //       Use the same redirect pattern as AuthMiddleware (RouteParser + responseFactory).
        if (!$is_authenticated) {
            FlashMessage::error('Please log in');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $loginUrl = $routeParser->urlFor('auth.login');

            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $loginUrl);
        }

        if ($role !== 'admin') {
            FlashMessage::error('Access denied');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('user.dashboard');

            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url);
        }

        return $handler->handle($request);
    }
}
