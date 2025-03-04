<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\File;

use Illuminate\Support\Facades\File;
use Spatie\QueueableAction\QueueableAction;

class CopyAction
{
    use QueueableAction;

    public function execute(string $from, string $to): void
    {
        if (! File::exists(\dirname($to))) {
            try {
                File::makeDirectory(\dirname($to), 0755, true, true);
            } catch (\Exception $e) {
                dd('Caught exception: ', $e->getMessage(), '\n['.__LINE__.']['.class_basename(static::class).']');
            }
        }

        if (File::exists($to)) {
            return;
        }

        if (app()->runningInConsole()) {
            return;
        }

        // not rewite
        try {
            File::copy($from, $to);
        } catch (\Exception $exception) {
            throw new \Exception('Unable to copy
                    from ['.$from.']
                    to ['.$to.']
                    message ['.$exception->getMessage().']', $exception->getCode(), $exception);
        }
    }
}
