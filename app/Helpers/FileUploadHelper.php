<?php

namespace App\Helpers;

use Psr\Http\Message\UploadedFileInterface;
use App\Helpers\Core\Result;

class FileUploadHelper
{
    public static function upload(UploadedFileInterface $uploadedFile, array $config): Result
    {
        $directory = $config['directory'] ?? null;
        $allowedTypes = $config['allowedTypes'] ?? [];
        $maxSize = $config['maxSize'] ?? 0;
        $filenamePrefix = $config['filenamePrefix'] ?? 'upload_';

        if (empty($directory)) {
            return Result::failure('Upload directory not specified in configuration');
        }

        if (empty($allowedTypes)) {
            return Result::failure('Allowed file types not specified in configuration');
        }

        if ($maxSize <= 0) {
            return Result::failure('Maximum file size not specified in configuration');
        }

        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return Result::failure('Error uploading file');
        }

        if ($uploadedFile->getSize() > $maxSize) {
            $maxSizeMb = round($maxSize / (1024 * 1024), 1);
            return Result::failure('File is too large. Maximum size is ' . $maxSizeMb . ' MB');
        }

        $mediaType = $uploadedFile->getClientMediaType();
        if (!in_array($mediaType, $allowedTypes)) {
            return Result::failure('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes));
        }

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $filename = uniqid($filenamePrefix) . '.' . $extension;

        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                return Result::failure('Failed to create upload directory');
            }
        }

        $destination = $directory . DIRECTORY_SEPARATOR . $filename;

        try {
            $uploadedFile->moveTo($destination);
        } catch (\Exception $e) {
            return Result::failure('Failed to save uploaded file: ' . $e->getMessage());
        }

        return Result::success('File uploaded successfully', [
            'filename' => $filename
        ]);
    }
}
