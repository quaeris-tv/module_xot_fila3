<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Mail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class SendMailByRecordAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     *
     * @return bool
     */
    public function execute(Model $record, string $mail_class)
    {
        if (! method_exists($record, 'canSendEmail')) {
            throw new \Exception('You need to define a canSendEmail method in your model ['.get_class($record).']');
        }
        if (! method_exists($record, 'myLogs')) {
            throw new \Exception('You need to define a myLogs method in your model ['.get_class($record).']');
        }

        if (! $record->canSendEmail()) {
            return false;
        }
        // non uso il gate perche' se no superadmin puo' far casino
        // if (! Gate::allows('send-mail', $record)) {
        //    dddx('NO');
        // }
        /*
        $record_class = get_class($record);
        $log_class = Str::of($record_class)
            ->before('\Models')
            ->append('\Models\MyLog')
            ->toString();
        */
        // $to = $record->attributes['email'] ?? null;

        $to = $record->email ?? null;
        // dddx($to);
        // $to = 'marco.sottana@gmail.com';

        Assert::isInstanceOf($mailable = new $mail_class($record), \Illuminate\Contracts\Mail\Mailable::class, '['.__LINE__.']['.class_basename($this).']');
        // $mailable = new $mail_class($record);
        if (null !== $to) {
            Mail::to($to)->send($mailable);
            $record->myLogs()->create(['act' => 'sendMail']);
        } else {
            // throw new \Exception('Email is null matr['.$record->getTable().']['.$record->getKey().']');
            throw new \Exception('['.__LINE__.']['.__CLASS__.']');
        }
        /*
        $log_class::create([
            'id_tbl'=>$record->id,
            'tbl'=>$record->getTable(),
            'act'=>'sendMail',
            'handle'=>Auth::id(),
        ]);
        */

        return true;
    }
}
