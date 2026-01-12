<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }

        // Register Google Cloud Storage driver
        \Illuminate\Support\Facades\Storage::extend('gcs', function ($app, $config) {
            $client = new \Google\Cloud\Storage\StorageClient([
                'projectId' => $config['project_id'] ?? env('GOOGLE_CLOUD_PROJECT_ID'),
                'keyFile' => $config['key_file'] ?? null,
            ]);

            $bucket = $client->bucket($config['bucket']);
            $adapter = new \League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter($bucket, $config['path_prefix'] ?? '');
            
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new \League\Flysystem\Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }
}
