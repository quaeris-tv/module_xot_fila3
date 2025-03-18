<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths(
        [
            __DIR__.'/Modules',
            __DIR__.'/app',
            __DIR__.'/bootstrap',
            __DIR__.'/config',
            __DIR__.'/lang',
            __DIR__.'/resources',
            __DIR__.'/routes',
            __DIR__.'/tests',
        ]
    );

    $rectorConfig->skip(
        [
            __DIR__.'/Modules/*/docs',
            __DIR__.'/Modules/*/vendor',
            '*/docs',
            '*/vendor',
            './vendor/',
        ]
    );

    // register a single rule
    // $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    // $rectorConfig->rule(RedirectRouteToToRouteHelperRector::class);

    // define sets of rules
    $rectorConfig->sets(
        [
            PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
            // SetList::DEAD_CODE,
            // SetList::CODE_QUALITY,
            LevelSetList::UP_TO_PHP_81,
            LaravelSetList::LARAVEL_100,

            // SetList::NAMING, //problemi con injuction
            SetList::TYPE_DECLARATION,
            // SetList::CODING_STYLE,
            // SetList::PRIVATIZATION,//problemi con final
            // SetList::EARLY_RETURN,
            // SetList::INSTANCEOF,
        ]
    );

    $rectorConfig->importNames();
};
