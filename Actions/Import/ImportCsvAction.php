<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Import;

use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Safe\ini_set;
use function Safe\preg_replace;

use Spatie\QueueableAction\QueueableAction;

class ImportCsvAction
{
    use QueueableAction;

    public function execute(string $disk, string $filename, string $db, string $tbl): void
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1'); // '512M'

        $storage = Storage::disk($disk);
        $path = $storage->path($filename);
        $path = Str::of($path)
            // ->replace('\\', DIRECTORY_SEPARATOR)
            // ->replace('/', DIRECTORY_SEPARATOR)
            ->replace('\\', '/')
            ->toString();
        $conn = Schema::connection($db);
        $pdo = DB::connection($db)->getPdo();
        $columns = $conn->getColumns($tbl);
        $excepts = ['id'];
        $columns = Arr::where($columns, function ($item) use ($excepts) {
            return ! in_array($item['name'], $excepts);
        });

        $fields_up = [];
        foreach ($columns as $item) {
            $fieldname = $this->fixFieldName($item['name']);
            // if ('numero' === $item['tipo'] && $item['dec'] > 0) {
            if ('decimal' === $item['type_name']) {
                $fieldname = '@'.$fieldname;
            }

            $fields_up[] = $fieldname;
        }

        $fields_up_list = implode(', ', $fields_up);

        $sql = "LOAD DATA LOW_PRIORITY LOCAL INFILE '".$path."'
	 INTO TABLE `".$db.'`.`'.$tbl."` character set latin1 FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\r\n' (".$fields_up_list.')'.\chr(13);
        $sql_replace = [];
        foreach ($columns as $item) {
            // if ('numero' === $item['tipo'] && $item['dec'] > 0) {
            if ('decimal' === $item['type_name']) {
                $fieldname = $this->fixFieldName($item['name']);
                $sql_replace[] = $fieldname.' = REPLACE(@'.$fieldname.',"," , ".")';
            }
        }

        $sql_replace = implode(', '.\chr(13), $sql_replace);
        if (mb_strlen($sql_replace) > 3) {
            $sql = $sql.'SET '.$sql_replace.';';
        }

        $pdo->exec('SET GLOBAL local_infile=1;');
        // echo '<pre>'.htmlspecialchars($sql).'</pre>';
        $n_rows = $pdo->exec($sql);
        // dddx($n_rows);
        Notification::make()
            ->title('Imported successfully')
            ->success()
            ->body($n_rows.' records')
            ->persistent()
            ->send();
    }

    public function fixFieldName(string $str): string
    {
        $str = trim($str);
        if ('desc' === $str) {
            return 'desc1';
        } // descrizione;
        // preg_match_all(, subject, matches)
        $str = str_replace('§', '10', $str);
        $str = str_replace('\xA7', '10', $str);
        $str = str_replace('$', '11', $str);

        $str1 = (string) preg_replace('/[0-9a-z]/i', '', $str);

        switch (\ord($str1)) {
            case 0:break;
            case 167: $str = str_replace($str1, '10', $str);
                break;
            case 239: $str = str_replace($str1, '_', $str);
                break;
            default:
                echo '<h3>carattere non riconosciuto ['.$str1.']['.\ord($str1).']['.$str.'] Aggiungerlo </h3>';
                exit('<hr/>['.__LINE__.']['.class_basename($this).']');
                // break;
        }

        return $str;
    }
}
