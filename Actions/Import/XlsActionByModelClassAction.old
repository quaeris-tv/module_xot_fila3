<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Import;

// use Modules\Xot\Services\ArrayService;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;
use Spatie\QueueableAction\QueueableAction;

class XlsActionByModelClassAction
{
    use QueueableAction;

    public function execute(string $modelClass): ImportAction
    {
        $model = app($modelClass);
        $fields = [];
        foreach ($model->getFillable() as $fillable) {
            $fields[] = ImportField::make($fillable)
                ->label($fillable);
            // ->helperText('Define as project helper')
        }

        return ImportAction::make()
            ->fields($fields)
            ->label('XLS')
            ->icon('heroicon-o-arrow-up-tray')
            ->tooltip('Import XLS');
    }
}
