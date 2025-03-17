<?php

declare(strict_types=1);

namespace Modules\Xot\DTOs;

use Spatie\LaravelData\Data;

/**
 * Undocumented class.
 */
class FieldDTO extends Data
{
    public string $param_name;

    /**
     * Undocumented variable.
     */
    public string|array|null $rules = null;

    /*
     * Undocumented function.
     */
    // public function __construct(
    //    public string $title,
    //    public string $content,
    // ) {
    // }
}
