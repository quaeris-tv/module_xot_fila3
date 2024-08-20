<?php

declare(strict_types=1);

namespace Modules\Xot\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Jurosh\PDFMerge\PDFMerger;

/**
 * Class PdfService.
 */
class PdfService
{
    public array $filenames = [];

    private static ?self $instance = null;

    public function __construct()
    {
        // ---
        include_once __DIR__.'/vendor/autoload.php';
    }

    public static function getInstance(): self
    {
        if (! self::$instance instanceof PdfService) {
<<<<<<< HEAD
            self::$instance = new self;
=======
            self::$instance = new self();
>>>>>>> 35d9347 (.)
        }

        return self::$instance;
    }

    public static function make(): self
    {
        return static::getInstance();
    }

    // include __DIR__.'/vendor/autoload.php';

    public function mergePdf(string $path): self
    {
        include __DIR__.'/vendor/autoload.php';
        // $path = $this->get('path');
        if (! class_exists(PDFMerger::class)) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

<<<<<<< HEAD
        $pdfMerger = new PDFMerger;
        $pdf_files = collect(File::files($path))->filter(
            static fn ($file, $key): bool => $file->getExtension() === 'pdf' && ! Str::startsWith($file->getBasename(), '_')
=======
        $pdfMerger = new PDFMerger();
        $pdf_files = collect(File::files($path))->filter(
            static fn ($file, $key): bool => 'pdf' === $file->getExtension() && ! Str::startsWith($file->getBasename(), '_')
>>>>>>> 35d9347 (.)
        );
        foreach ($this->filenames as $filename) {
            // $pdf->addPDF($filename.'.pdf');
            $pdfMerger->addPDF($filename);
        }

        foreach ($pdf_files as $pdf_file) {
            $pdf_path = $pdf_file->getRealPath();
            // echo '<br/> ADD: '.$pdf_path;
            // if(! Str::startsWith($file, '_')
            $pdfMerger->addPDF($pdf_path);
        }

        $pdfMerger->merge('file', $path.'/_all.pdf');

        return $this;
    }

    public function addFilenames(array $filenames): self
    {
        $this->filenames = array_merge($this->filenames, $filenames);

        return $this;
    }
}
