<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\SessionManager;
use App\Helpers\TranslationHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class LocaleMiddleware implements MiddlewareInterface
{
    private TranslationHelper $translator;

    public function __construct(TranslationHelper $translator)
    {
        $this->translator = $translator;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $params    = $request->getQueryParams();
        $urlLocale = trim($params['lang'] ?? '');

        if ($urlLocale !== '' && $this->translator->isLocaleAvailable($urlLocale)) {
            // URL param is valid: update translator + save to session
            $this->translator->setLocale($urlLocale);
            SessionManager::set('locale', $urlLocale);
        } else {
            // Fall back to session locale, or default
            $sessionLocale = SessionManager::get('locale', $this->translator->getDefaultLocale());
            if ($this->translator->isLocaleAvailable($sessionLocale)) {
                $this->translator->setLocale($sessionLocale);
            }
        }

        // Store current locale as a request attribute (useful in controllers/views)
        $request = $request->withAttribute('locale', $this->translator->getLocale());

        return $handler->handle($request);
    }
}
