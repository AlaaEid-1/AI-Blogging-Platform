<?php

namespace App\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FileUpload
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(string $key, $path = '/', $disk = 'public')
    {
        $file = $this->request->file($key);

        if (! $file) {
            return null;
        }

        if (! $file->isValid()) {
            $errorMessage = $file->getErrorMessage();
            Log::error("File upload failed for key '{$key}': {$errorMessage}");

            throw ValidationException::withMessages([
                $key => ['The uploaded file is invalid or could not be processed.'],
            ]);
        }

        return $file->store($path, $disk);
    }
}
