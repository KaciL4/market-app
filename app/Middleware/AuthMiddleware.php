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

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private ResponseFactoryInterface $responseFactory) {}

    /**
     * Process the request - check if user is authenticated.
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        // TODO: Retrieve the user's authentication status from the session.
        //       If not authenticated, display an error flash message and redirect to the login page.
        //       If authenticated, allow the request to proceed.
        //
        //       Use the following to generate the login URL and create a redirect response:
        //       $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        //       $loginUrl = $routeParser->urlFor('auth.login');
        //       $response = $this->responseFactory->createResponse(302);
        //       return $response->withHeader('Location', $loginUrl);
        $is_authenticated = SessionManager::get('is_authenticated');

        if (!$is_authenticated) {
            FlashMessage::error('Please log in to access this page');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $loginUrl = $routeParser->urlFor('auth.login');

            $response = $this->responseFactory->createResponse(302);
            return $response->withHeader('Location', $loginUrl);
        }

        return $handler->handle($request);
    }
}
