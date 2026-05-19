<?php

declare(strict_types=1);

namespace App\Helpers;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\JsonFileLoader;

class TranslationHelper
{
    private Translator $translator;
    private string $currentLocale;
    private string $defaultLocale;
    private string $langPath;
    private array $availableLocales;

    public function __construct(
        string $langPath,
        string $defaultLocale = 'en',
        array $availableLocales = ['en', 'fr']
    ) {
        $this->langPath       = $langPath;
        $this->defaultLocale  = $defaultLocale;
        $this->currentLocale  = $defaultLocale;
        $this->availableLocales = $availableLocales;

        $this->translator = new Translator($defaultLocale);
        $this->translator->setFallbackLocales([$defaultLocale]);
        $this->translator->addLoader('json', new JsonFileLoader());

        $this->loadTranslations();
    }

    private function loadTranslations(): void
    {
        foreach ($this->availableLocales as $locale) {
            $filePath = $this->langPath . '/' . $locale . '/messages.json';
            if (file_exists($filePath)) {
                $this->translator->addResource('json', $filePath, $locale, 'messages');
            }
        }
    }

    public function trans(string $key, array $parameters = [], ?string $locale = null): string
    {
        return $this->translator->trans($key, $parameters, 'messages', $locale ?? $this->currentLocale);
    }

    public function setLocale(string $locale): void
    {
        if (!$this->isLocaleAvailable($locale)) {
            throw new \InvalidArgumentException("Locale '$locale' is not available.");
        }
        $this->currentLocale = $locale;
        $this->translator->setLocale($locale);
    }

    public function getLocale(): string
    {
        return $this->currentLocale;
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    public function getAvailableLocales(): array
    {
        return $this->availableLocales;
    }

    public function isLocaleAvailable(string $locale): bool
    {
        return in_array($locale, $this->availableLocales, true);
    }
}
