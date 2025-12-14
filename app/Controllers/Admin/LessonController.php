<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LessonModel;
use CodeIgniter\HTTP\ResponseInterface;

class LessonController extends BaseController
{
    protected $lessonModel;

    public function __construct()
    {
        $this->lessonModel = new LessonModel();
    }

    public function create($sectionId)
    {
        $data = $this->request->getJSON(true);
        $data['section_id'] = $sectionId;

        $rules = [
            'title'        => 'required|min_length[3]',
            'content_type' => 'required|in_list[video,article,file,quiz]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $lessonId = $this->lessonModel->insert($data);
        $lesson = $this->lessonModel->find($lessonId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Lesson created successfully',
            'data'    => $lesson
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($id)
    {
        $lesson = $this->lessonModel->find($id);

        if (!$lesson) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Lesson not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);
        $this->lessonModel->update($id, $data);

        $updatedLesson = $this->lessonModel->find($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Lesson updated successfully',
            'data'    => $updatedLesson
        ]);
    }

    public function delete($id)
    {
        $lesson = $this->lessonModel->find($id);

        if (!$lesson) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Lesson not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->lessonModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Lesson deleted successfully'
        ]);
    }
}
