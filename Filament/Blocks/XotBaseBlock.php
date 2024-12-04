<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Modules\Xot\Actions\Filament\Block\GetViewBlocksOptionsByTypeAction;

abstract class XotBaseBlock
{
    public static function make(
        string $name = 'article_list',
        string $context = 'form',
    ): Block {
        $schema = array_merge(static::getBlockSchema(), static::getBlockVarSchema());

        return Block::make($name)
            ->schema($schema)

            ->columns('form' === $context ? 3 : 1);
    }

    public static function getBlockSchema(): array
    {
        return [];
    }

    public static function getBlockVarSchema(): array
    {
        $options = app(GetViewBlocksOptionsByTypeAction::class)
           ->execute('article_list', false);

        return [
            Select::make('view')
                       ->options($options),
        ];
    }
}
