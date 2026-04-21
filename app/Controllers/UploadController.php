<?php

namespace App\Controllers;

use App\Helpers\FileUploadHelper;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UploadController extends BaseController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $files = scandir(APP_BASE_DIR_PATH . '/public/uploads/images');
        $files = array_diff($files, ['.', '..']);

        $data = [
            'title' => 'File Upload',
            'files' => $files
        ];

        return $this->render($response, 'admin/upload/uploadView.php', $data);
    }

    public function upload(Request $request, Response $response, array $args): Response
    {
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['myfile'] ?? null;

        if (!$uploadedFile) {
            FlashMessage::error('No file selected');
            return $response
                ->withHeader('Location', APP_BASE_URL . '/admin/upload')
                ->withStatus(302);
        }

        $config = [
            'directory' => APP_BASE_DIR_PATH . '/public/uploads/images',
            'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif'],
            'maxSize' => 2 * 1024 * 1024,
            'filenamePrefix' => 'upload_'
        ];

        $result = FileUploadHelper::upload($uploadedFile, $config);

        if ($result->isSuccess()) {
            $filename = $result->getData()['filename'];

            $files = SessionManager::get('uploaded_files', []);
            $files[] = $filename;
            SessionManager::set('uploaded_files', $files);

            FlashMessage::success('File uploaded successfully');
        } else {
            FlashMessage::error($result->getMessage());
        }

        return $response
            ->withHeader('Location', APP_BASE_URL . '/admin/upload')
            ->withStatus(302);
    }

    public function delete(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $filename = $data['filename'] ?? '';

        $path = APP_BASE_DIR_PATH . '/public/uploads/images/' . $filename;

        if (file_exists($path)) {
            unlink($path);
            FlashMessage::success("File deleted");
        } else {
            FlashMessage::error("File not found");
        }

        return $response
            ->withHeader('Location', APP_BASE_URL . '/admin/upload')
            ->withStatus(302);
    }
}
