<?php

declare(strict_types=1);

namespace Modules\Xot\Exceptions\Formatters;

use Illuminate\Support\Facades\Auth;

// use Symfony\Component\HttpFoundation\Request;

class WebhookErrorFormatter
{
    // private Request $request;

    public function __construct(private readonly \Throwable $exception)
    {
        // $this->request = $request;
    }

    public function format(): array
    {
        $user = Auth::user();
        $email = 'CLI User';
        if ($user !== null) {
            $email = $user->email;
        }

        return [
            'exception' => '`'.$this->exception::class.sprintf('` (Code `%s`)', $this->exception->getCode()),
            'thrown_in' => sprintf('`%s`:%d', $this->exception->getFile(), $this->exception->getLine()),
            'user' => sprintf(
                '%d <%s>',
                Auth::id(),
                $email
            ),
            'ip' => request()->ip(),
            // Request::ip();
            'thrown_while_calling' => sprintf(
                '[%s] %s',
                request()->getMethod(),
                request()->fullUrl()
            ),
            'url_previous' => url()->previous(),
            /*
            'exception_details' => sprintf(
                "Trace:\n```json \n %s \n ```\n\n Previous: \n `%s`",
                json_encode($this->exception->getTrace(), JSON_PRETTY_PRINT),
                $this->exception->getPrevious() ? ('`'.get_class($this->exception->getPrevious()).'`') : 'None'
            ),
            */
        ];
    }
}
