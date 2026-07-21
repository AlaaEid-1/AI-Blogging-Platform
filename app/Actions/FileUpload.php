<?php

namespace App\Actions;

use Illuminate\Http\Request;

class FileUpload
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(string $key, $path = '/', $disk = 'public')
    {
        $file = $this->request->file($key);
        if (!$file) {
            return null;
        }

        return $file->store($path, $disk);
    }
}
