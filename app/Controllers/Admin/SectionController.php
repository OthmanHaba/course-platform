<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SectionModel;
use CodeIgniter\HTTP\ResponseInterface;

class SectionController extends BaseController
{
    protected $sectionModel;

    public function __construct()
    {
        $this->sectionModel = new SectionModel();
    }

    public function create($courseId)
    {
        $data = $this->request->getJSON(true);
        $data['course_id'] = $courseId;

        $rules = [
            'title' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $sectionId = $this->sectionModel->insert($data);
        $section = $this->sectionModel->find($sectionId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Section created successfully',
            'data'    => $section
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($id)
    {
        $section = $this->sectionModel->find($id);

        if (!$section) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Section not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);
        $this->sectionModel->update($id, $data);

        $updatedSection = $this->sectionModel->find($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Section updated successfully',
            'data'    => $updatedSection
        ]);
    }

    public function delete($id)
    {
        $section = $this->sectionModel->find($id);

        if (!$section) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Section not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->sectionModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Section deleted successfully'
        ]);
    }
}
