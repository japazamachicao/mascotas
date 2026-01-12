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
            
            // Use UniformBucketLevelAccessVisibility for public URLs
            $adapter = new \League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter(
                $bucket, 
                $config['path_prefix'] ?? '',
                new \League\Flysystem\GoogleCloudStorage\UniformBucketLevelAccessVisibility()
            );
            
            $driver = new \Illuminate\Filesystem\FilesystemAdapter(
                new \League\Flysystem\Filesystem($adapter, $config),
                $adapter,
                $config
            );

            // Set URL generator for public access
            $driver->buildTemporaryUrlsUsing(function ($path, $expiration, $options) use ($config) {
                return sprintf(
                    'https://storage.googleapis.com/%s/%s',
                    $config['bucket'],
                    ltrim($path, '/')
                );
            });

            return $driver;
        });
    }
}
