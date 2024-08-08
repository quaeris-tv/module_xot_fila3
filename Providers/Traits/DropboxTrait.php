<?php

declare(strict_types=1);

namespace Modules\Xot\Providers\Traits;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

trait DropboxTrait
{
    // lo riabilitiamo in futuro
    private function registerDropbox(): void
    {
        Storage::extend(
            'dropbox',
            static function ($app, array $config): Filesystem {
                // dddx($config);
                $client = new DropboxClient($config['authorizationToken']);
                $dropboxAdapter = new DropboxAdapter($client);

                return new Filesystem($dropboxAdapter, ['case_sensitive' => false]);
            }
        );
    }
}
