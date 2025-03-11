<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Mail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class SendMailByRecordAction
{
    use QueueableAction;

    /**
     * Invia una mail utilizzando un record come dati.
     *
     * @param Model $record Il record da utilizzare come dati per la mail
     * @param string $mailClass La classe Mailable da utilizzare
     * @return void
     */
    public function execute(Model $record, string $mailClass): void
    {
        Assert::classExists($mailClass);
        Assert::implementsInterface($mailClass, Mailable::class);

        // Utilizziamo il container per istanziare la classe Mailable
        // in modo che possa ricevere le dipendenze necessarie
        /** @var Mailable $mail */
        $mail = app($mailClass, ['record' => $record]);
        Mail::send($mail);
    }
}
