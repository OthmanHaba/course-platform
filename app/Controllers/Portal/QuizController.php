<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\QuizAttemptModel;
use App\Models\EnrollmentModel;
use App\Models\LessonModel;
use CodeIgniter\HTTP\ResponseInterface;

class QuizController extends BaseController
{
    protected $quizModel;
    protected $questionModel;
    protected $quizAttemptModel;
    protected $enrollmentModel;
    protected $lessonModel;

    public function __construct()
    {
        $this->quizModel = new QuizModel();
        $this->questionModel = new QuestionModel();
        $this->quizAttemptModel = new QuizAttemptModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->lessonModel = new LessonModel();
    }

    public function show($quizId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $quiz = $this->quizModel
            ->select('quizzes.*, lessons.section_id, sections.course_id')
            ->join('lessons', 'lessons.id = quizzes.lesson_id')
            ->join('sections', 'sections.id = lessons.section_id')
            ->where('quizzes.id', $quizId)
            ->first();

        if (!$quiz) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Quiz not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $enrollment = $this->enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $quiz['course_id'])
            ->first();

        if (!$enrollment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Not enrolled in this course'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $questions = $this->questionModel
            ->select('id, question_text, question_type, options, points, order_number')
            ->where('quiz_id', $quizId)
            ->orderBy('order_number', 'ASC')
            ->findAll();

        if ($quiz['shuffle_questions']) {
            shuffle($questions);
        }

        $previousAttempts = $this->quizAttemptModel
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->countAllResults();

        if ($quiz['max_attempts'] > 0 && $previousAttempts >= $quiz['max_attempts']) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Maximum attempts reached'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        unset($quiz['course_id']);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'quiz'      => $quiz,
                'questions' => $questions,
                'attempts'  => $previousAttempts
            ]
        ]);
    }

    public function submit($quizId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $quiz = $this->quizModel->find($quizId);

        if (!$quiz) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Quiz not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $answers = $this->request->getJSON(true)['answers'] ?? [];
        $startedAt = $this->request->getJSON(true)['started_at'] ?? date('Y-m-d H:i:s');

        $questions = $this->questionModel->where('quiz_id', $quizId)->findAll();

        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($questions as $question) {
            $totalPoints += $question['points'];

            if (isset($answers[$question['id']])) {
                $userAnswer = $answers[$question['id']];
                $correctAnswer = $question['correct_answer'];

                if ($question['question_type'] === 'multiple_choice' || $question['question_type'] === 'true_false') {
                    if ($userAnswer == $correctAnswer) {
                        $earnedPoints += $question['points'];
                    }
                } elseif ($question['question_type'] === 'short_answer') {
                    if (strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer))) {
                        $earnedPoints += $question['points'];
                    }
                }
            }
        }

        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $isPassed = $score >= $quiz['pass_percentage'];

        $attemptNumber = $this->quizAttemptModel
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->countAllResults() + 1;

        $timeTaken = strtotime('now') - strtotime($startedAt);

        $attemptData = [
            'user_id'        => $userId,
            'quiz_id'        => $quizId,
            'attempt_number' => $attemptNumber,
            'answers'        => json_encode($answers),
            'score'          => $score,
            'total_points'   => $totalPoints,
            'earned_points'  => $earnedPoints,
            'is_passed'      => $isPassed,
            'time_taken'     => $timeTaken,
            'started_at'     => $startedAt,
            'completed_at'   => date('Y-m-d H:i:s')
        ];

        $attemptId = $this->quizAttemptModel->insert($attemptData);

        $result = [
            'attempt_id'    => $attemptId,
            'score'         => $score,
            'total_points'  => $totalPoints,
            'earned_points' => $earnedPoints,
            'is_passed'     => $isPassed,
            'pass_percentage' => $quiz['pass_percentage']
        ];

        if ($quiz['show_results']) {
            $questionsWithResults = [];

            foreach ($questions as $question) {
                $userAnswer = $answers[$question['id']] ?? null;
                $isCorrect = false;

                if ($question['question_type'] === 'multiple_choice' || $question['question_type'] === 'true_false') {
                    $isCorrect = $userAnswer == $question['correct_answer'];
                } elseif ($question['question_type'] === 'short_answer') {
                    $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($question['correct_answer']));
                }

                $questionsWithResults[] = [
                    'question_id'    => $question['id'],
                    'question_text'  => $question['question_text'],
                    'your_answer'    => $userAnswer,
                    'correct_answer' => $question['correct_answer'],
                    'is_correct'     => $isCorrect,
                    'explanation'    => $question['explanation']
                ];
            }

            $result['questions'] = $questionsWithResults;
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $result
        ]);
    }

    public function result($attemptId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $attempt = $this->quizAttemptModel
            ->where('id', $attemptId)
            ->where('user_id', $userId)
            ->first();

        if (!$attempt) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Attempt not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $attempt
        ]);
    }

    public function attempts($quizId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $attempts = $this->quizAttemptModel
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->orderBy('attempt_number', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $attempts
        ]);
    }
}
