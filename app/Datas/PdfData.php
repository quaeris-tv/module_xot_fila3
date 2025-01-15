<?php

/*
 * @see https://github.com/masterix21/laravel-html2pdf/blob/master/src/PDF.php
 */

declare(strict_types=1);

namespace Modules\Xot\Datas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webmozart\Assert\Assert;

/**
 * Undocumented class.
 */
class PdfData extends Data
{
    /**
     * @var non-falsy-string
     */
    public string $filename = 'my_doc.pdf';

    public string $disk = 'cache';

    public string $out = 'download';

    // -- per costruttore
    public string $orientation = 'P';

    public string $format = 'A4';

    public string $lang = 'it';

    public bool $unicode = true;

    public string $encoding = 'UTF-8';

    public array $margins = [5, 5, 5, 8];

    public bool $pdfa = false;

    public string $dest = 'F';
    /*
        Dest can be :
        I : send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
        D : send to the browser and force a file download with the name given by name.
        F : save to a local server file with the name given by name.
        S : return the document as a string (name is ignored).
        FI: equivalent to F + I option
        FD: equivalent to F + D option
        E : return the document as base64 mime multi-part email attachment
        */

    // public static function make(Model $model = null, string $html = null): self
    public static function make(): self
    {
        return self::from([]);
    }

    public function getPath(): string
    {
        return Storage::disk($this->disk)->path($this->filename);
    }

    public function download(): BinaryFileResponse
    {
        $headers = [
            'Content-Type' => 'application/pdf',
        ];

        return response()->download($this->getPath(), $this->filename, $headers);
    }

    public function fromHtml(string $html): self
    {
        $html2pdf = new Html2Pdf($this->orientation, $this->format, $this->lang);
        $html2pdf->writeHTML($html);
        $html2pdf->output($this->getPath(), $this->dest);

        return $this;
    }

    public function fromModel(Model $model): self
    {
        $model_class = $model::class;
        $model_name = class_basename($model_class);
        $module = Str::between($model_class, '\Modules\\', '\Models');
        /**
         * @var non-falsy-string&view-string
         */
        $view_name = mb_strtolower($module).'::'.Str::kebab($model_name).'.show.pdf';
        $view_params = [
            'view' => $view_name,
            'row' => $model,
        ];
        $view = view($view_name, $view_params);
        $html = $view->render();

        return $this->fromHtml($html);
    }

    public function getContent(): string
    {
        Assert::notNull($res = Storage::disk($this->disk)->get($this->filename), '['.__LINE__.']['.class_basename($this).']');

        return $res;
    }
}
