<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\QuizModel;
use App\Models\QuestionModel;
use CodeIgniter\HTTP\ResponseInterface;

class QuizController extends BaseController
{
    protected $quizModel;
    protected $questionModel;

    public function __construct()
    {
        $this->quizModel = new QuizModel();
        $this->questionModel = new QuestionModel();
    }

    public function show($id)
    {
        $quiz = $this->quizModel->find($id);

        if (!$quiz) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Quiz not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        // Fetch questions for this quiz
        $questions = $this->questionModel
            ->where('quiz_id', $id)
            ->orderBy('order_number', 'ASC')
            ->findAll();

        $quiz['questions'] = $questions;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $quiz
        ]);
    }

    public function create($lessonId)
    {
        $data = $this->request->getJSON(true);
        $data['lesson_id'] = $lessonId;

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

        $quizId = $this->quizModel->insert($data);
        $quiz = $this->quizModel->find($quizId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Quiz created successfully',
            'data'    => $quiz
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($id)
    {
        $quiz = $this->quizModel->find($id);

        if (!$quiz) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Quiz not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);
        $this->quizModel->update($id, $data);

        $updatedQuiz = $this->quizModel->find($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Quiz updated successfully',
            'data'    => $updatedQuiz
        ]);
    }

    public function delete($id)
    {
        $quiz = $this->quizModel->find($id);

        if (!$quiz) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Quiz not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->quizModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Quiz deleted successfully'
        ]);
    }

    public function addQuestion($quizId)
    {
        $data = $this->request->getJSON(true);
        $data['quiz_id'] = $quizId;

        $rules = [
            'question_text'  => 'required',
            'question_type'  => 'required|in_list[multiple_choice,true_false,short_answer]',
            'correct_answer' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $questionId = $this->questionModel->insert($data);
        $question = $this->questionModel->find($questionId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Question added successfully',
            'data'    => $question
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function updateQuestion($id)
    {
        $question = $this->questionModel->find($id);

        if (!$question) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Question not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);
        $this->questionModel->update($id, $data);

        $updatedQuestion = $this->questionModel->find($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Question updated successfully',
            'data'    => $updatedQuestion
        ]);
    }

    public function deleteQuestion($id)
    {
        $question = $this->questionModel->find($id);

        if (!$question) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Question not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->questionModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Question deleted successfully'
        ]);
    }
}
