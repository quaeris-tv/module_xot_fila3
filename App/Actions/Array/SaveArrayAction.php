<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Array;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class SaveArrayAction
{
    use QueueableAction;

    public function execute(array $data, string $filename): void
    {
        $content = var_export($data, true);

        // HHVM fails at __set_state, so just use object cast for now
        $content = str_replace('stdClass::__set_state', '(object)', $content);

        $content = '<?php '.\chr(13).'return '.$content.';'.\chr(13);
        // $content = str_replace('stdClass::__set_state', '(object)', $content);
        File::makeDirectory(\dirname((string) $filename), 0775, true, true);
        File::put($filename, $content);
    }
}
