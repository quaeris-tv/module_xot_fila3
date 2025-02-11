<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Mail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class SendMailByRecordAction
{
    public function execute(Model $model, string $mail_class): void
    {
        $mailable = new Mailable();
        $view='pub_theme::mail.record';
        if(!view()->exists($view)){
            throw new RuntimeException('view not exists: '.$view);
        }
        $view_params=['model' => $model];
        $mailable->view($view)
            ->with($view_params);
        // @phpstan-ignore property.notFound
        $email=$model->email;
        Mail::to($email)->send($mailable);
    }
}
