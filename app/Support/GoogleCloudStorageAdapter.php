<?php

namespace App\Support;

use Illuminate\Filesystem\FilesystemAdapter;

class GoogleCloudStorageAdapter extends FilesystemAdapter
{
    protected $bucket;

    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * Get the URL for the file at the given path.
     *
     * @param  string  $path
     * @return string
     */
    public function url($path)
    {
        return sprintf(
            'https://storage.googleapis.com/%s/%s',
            $this->bucket,
            ltrim($path, '/')
        );
    }
}
