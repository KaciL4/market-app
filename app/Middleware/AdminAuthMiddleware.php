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
        // TODO: Retrieve the user's authentication status and role from the session.
        $user =SessionManager::get('user');
        $authStatus= isset($user['is_auth'])&& $user['is_auth']===true;
        $role = SessionManager::get('role');
        //       If not authenticated, redirect to the login page.
        if(!$authStatus){
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();

            $loginUrl = $routeParser->urlFor('auth.login');
            $response = $this->responseFactory->createResponse(302);
            return $response->withHeader('Location', $loginUrl);
        }
        //       If authenticated but not an admin, redirect to the user dashboard
        //       with an access denied message.
        else if($authStatus && strtolower($user['role']) !== 'admin'){
            FlashMessage::error('Admin access is denied.');
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();

            $dashboardUrl = $routeParser->urlFor('user.dashboard');
            $response = $this->responseFactory->createResponse(302);
            return $response->withHeader('Location', $dashboardUrl);

        }
        else{
            return $handler->handle($request);
        }
    }
}
