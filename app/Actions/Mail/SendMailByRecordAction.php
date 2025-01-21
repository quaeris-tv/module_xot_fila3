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
use Illuminate\Mail\Mailable;

class SendMailByRecordAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     *
     * @return bool
     */
    public function execute(Model $record, string $mailClass): void
    {
        Assert::classExists($mailClass);
        Assert::implementsInterface($mailClass, Mailable::class);
        
        /** @var Mailable $mail */
        $mail = new $mailClass($record);
        $mail->send();
    }
}
