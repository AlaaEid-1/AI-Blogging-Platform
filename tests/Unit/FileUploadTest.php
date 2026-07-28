<?php

namespace Tests\Unit;

use App\Actions\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    public function test_returns_null_when_no_file_uploaded()
    {
        $request = new Request;
        $action = new FileUpload($request);

        $result = $action->handle('cover', 'covers', 'public');

        $this->assertNull($result);
    }

    public function test_stores_valid_file_and_returns_path()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('cover.txt', 10, 'text/plain');
        $request = new Request([], [], [], [], ['cover' => $file]);

        $action = new FileUpload($request);
        $result = $action->handle('cover', 'covers', 'public');

        $this->assertNotNull($result);
        Storage::disk('public')->assertExists($result);
    }

    public function test_throws_validation_exception_for_invalid_file()
    {
        $file = new UploadedFile(
            path: 'invalid/path.jpg',
            originalName: 'path.jpg',
            mimeType: 'image/jpeg',
            error: UPLOAD_ERR_INI_SIZE,
            test: true
        );

        $request = new Request([], [], [], [], ['cover' => $file]);
        $action = new FileUpload($request);

        $this->expectException(ValidationException::class);

        $action->handle('cover', 'covers', 'public');
    }
}
