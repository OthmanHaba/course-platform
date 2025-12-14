<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MediaModel;
use CodeIgniter\HTTP\ResponseInterface;

class MediaController extends BaseController
{
    protected $mediaModel;

    public function __construct()
    {
        $this->mediaModel = new MediaModel();
    }

    public function upload()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'file' => 'uploaded[file]|max_size[file,51200]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validation->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $file->getErrorString()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $fileName = $file->getRandomName();
        $filePath = $file->store('uploads', $fileName);

        $fileType = 'other';
        if ($file->getClientMimeType()) {
            if (str_starts_with($file->getClientMimeType(), 'image/')) {
                $fileType = 'image';
            } elseif (str_starts_with($file->getClientMimeType(), 'video/')) {
                $fileType = 'video';
            } elseif (in_array($file->getClientExtension(), ['pdf', 'doc', 'docx', 'ppt', 'pptx'])) {
                $fileType = 'document';
            }
        }

        $data = [
            'file_name'   => $file->getName(),
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'mime_type'   => $file->getClientMimeType(),
            'file_size'   => $file->getSize(),
            'uploaded_by' => $this->request->getPost('uploaded_by'),
            'entity_type' => $this->request->getPost('entity_type'),
            'entity_id'   => $this->request->getPost('entity_id')
        ];

        $mediaId = $this->mediaModel->insert($data);
        $media = $this->mediaModel->find($mediaId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'File uploaded successfully',
            'data'    => $media
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function delete($id)
    {
        $media = $this->mediaModel->find($id);

        if (!$media) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Media not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if (file_exists(WRITEPATH . 'uploads/' . $media['file_path'])) {
            unlink(WRITEPATH . 'uploads/' . $media['file_path']);
        }

        $this->mediaModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Media deleted successfully'
        ]);
    }
}
