<?php

declare(strict_types=1);

namespace Modules\Xot\Providers\Traits;

// --- services ---
use Illuminate\Translation\Translator;
use Modules\Xot\Services\TranslatorService;

trait TranslatorTrait
{
    public function registerTranslator(): void
    {
        // Override the JSON Translator
        $this->app->extend(
            'translator',
            static function (Translator $translator): TranslatorService {
                $translatorService = new TranslatorService($translator->getLoader(), $translator->getLocale());
                $translatorService->setFallback($translator->getFallback());

                return $translatorService;
            }
        );
    }
}
