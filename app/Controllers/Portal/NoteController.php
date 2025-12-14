<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\NoteModel;
use App\Models\LessonModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class NoteController extends BaseController
{
    protected $noteModel;
    protected $lessonModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->noteModel = new NoteModel();
        $this->lessonModel = new LessonModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index($lessonId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $notes = $this->noteModel
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->orderBy('video_timestamp', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $notes
        ]);
    }

    public function create($lessonId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $lesson = $this->lessonModel
            ->select('lessons.*, sections.course_id')
            ->join('sections', 'sections.id = lessons.section_id')
            ->where('lessons.id', $lessonId)
            ->first();

        if (!$lesson) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Lesson not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $enrollment = $this->enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $lesson['course_id'])
            ->first();

        if (!$enrollment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Not enrolled in this course'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $rules = [
            'note_text' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data = [
            'user_id'         => $userId,
            'lesson_id'       => $lessonId,
            'note_text'       => $this->request->getJSON(true)['note_text'],
            'video_timestamp' => $this->request->getJSON(true)['video_timestamp'] ?? null
        ];

        $noteId = $this->noteModel->insert($data);
        $note = $this->noteModel->find($noteId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Note created successfully',
            'data'    => $note
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($noteId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $note = $this->noteModel
            ->where('id', $noteId)
            ->where('user_id', $userId)
            ->first();

        if (!$note) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Note not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);

        $updateData = [];
        if (isset($data['note_text'])) {
            $updateData['note_text'] = $data['note_text'];
        }
        if (isset($data['video_timestamp'])) {
            $updateData['video_timestamp'] = $data['video_timestamp'];
        }

        $this->noteModel->update($noteId, $updateData);

        $updatedNote = $this->noteModel->find($noteId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Note updated successfully',
            'data'    => $updatedNote
        ]);
    }

    public function delete($noteId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $note = $this->noteModel
            ->where('id', $noteId)
            ->where('user_id', $userId)
            ->first();

        if (!$note) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Note not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->noteModel->delete($noteId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Note deleted successfully'
        ]);
    }
}
