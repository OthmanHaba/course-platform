<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\SectionModel;
use App\Models\LessonModel;
use App\Models\ProgressModel;
use CodeIgniter\HTTP\ResponseInterface;

class LearningController extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $sectionModel;
    protected $lessonModel;
    protected $progressModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->sectionModel = new SectionModel();
        $this->lessonModel = new LessonModel();
        $this->progressModel = new ProgressModel();
    }

    public function curriculum($courseId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $enrollment = $this->enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Not enrolled in this course'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $sections = $this->sectionModel
            ->where('course_id', $courseId)
            ->orderBy('order_number', 'ASC')
            ->findAll();

        foreach ($sections as &$section) {
            $lessons = $this->lessonModel
                ->where('section_id', $section['id'])
                ->where('is_published', 1)
                ->orderBy('order_number', 'ASC')
                ->findAll();

            foreach ($lessons as &$lesson) {
                $progress = $this->progressModel
                    ->where('user_id', $userId)
                    ->where('lesson_id', $lesson['id'])
                    ->first();

                $lesson['is_completed'] = $progress ? $progress['is_completed'] : 0;
            }

            $section['lessons'] = $lessons;
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $sections
        ]);
    }

    public function lesson($lessonId)
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
            ->where('lessons.is_published', 1)
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

        $this->enrollmentModel->update($enrollment['id'], [
            'last_accessed_lesson_id' => $lessonId,
            'last_accessed_at'        => date('Y-m-d H:i:s')
        ]);

        $progress = $this->progressModel
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();

        $lesson['progress'] = $progress;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $lesson
        ]);
    }

    public function completeLesson($lessonId)
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

        $existingProgress = $this->progressModel
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();

        if ($existingProgress) {
            $this->progressModel->update($existingProgress['id'], [
                'is_completed' => 1,
                'completed_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->progressModel->insert([
                'user_id'      => $userId,
                'lesson_id'    => $lessonId,
                'is_completed' => 1,
                'completed_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->updateEnrollmentProgress($userId, $lesson['course_id']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Lesson marked as complete'
        ]);
    }

    public function nextLesson($lessonId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $currentLesson = $this->lessonModel
            ->select('lessons.*, sections.course_id')
            ->join('sections', 'sections.id = lessons.section_id')
            ->where('lessons.id', $lessonId)
            ->first();

        if (!$currentLesson) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Lesson not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $nextLesson = $this->lessonModel
            ->where('section_id', $currentLesson['section_id'])
            ->where('order_number >', $currentLesson['order_number'])
            ->where('is_published', 1)
            ->orderBy('order_number', 'ASC')
            ->first();

        if (!$nextLesson) {
            $nextSection = $this->sectionModel
                ->where('course_id', $currentLesson['course_id'])
                ->where('order_number >', $currentLesson['order_number'])
                ->orderBy('order_number', 'ASC')
                ->first();

            if ($nextSection) {
                $nextLesson = $this->lessonModel
                    ->where('section_id', $nextSection['id'])
                    ->where('is_published', 1)
                    ->orderBy('order_number', 'ASC')
                    ->first();
            }
        }

        if (!$nextLesson) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'No more lessons',
                'data'    => null
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $nextLesson
        ]);
    }

    public function courseProgress($courseId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $enrollment = $this->enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Not enrolled in this course'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'progress_percentage' => $enrollment['progress_percentage'],
                'is_completed'        => $enrollment['is_completed'],
                'completion_date'     => $enrollment['completion_date']
            ]
        ]);
    }

    public function overallProgress()
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $enrollments = $this->enrollmentModel
            ->select('enrollments.*, courses.title')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $userId)
            ->findAll();

        $totalEnrollments = count($enrollments);
        $completedCourses = 0;
        $inProgressCourses = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment['is_completed']) {
                $completedCourses++;
            } elseif ($enrollment['progress_percentage'] > 0) {
                $inProgressCourses++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'total_enrollments'   => $totalEnrollments,
                'completed_courses'   => $completedCourses,
                'in_progress_courses' => $inProgressCourses,
                'enrollments'         => $enrollments
            ]
        ]);
    }

    private function updateEnrollmentProgress($userId, $courseId)
    {
        $sections = $this->sectionModel->where('course_id', $courseId)->findAll();
        $totalLessons = 0;
        $completedLessons = 0;

        foreach ($sections as $section) {
            $lessons = $this->lessonModel
                ->where('section_id', $section['id'])
                ->where('is_published', 1)
                ->findAll();

            $totalLessons += count($lessons);

            foreach ($lessons as $lesson) {
                $progress = $this->progressModel
                    ->where('user_id', $userId)
                    ->where('lesson_id', $lesson['id'])
                    ->where('is_completed', 1)
                    ->first();

                if ($progress) {
                    $completedLessons++;
                }
            }
        }

        $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        $isCompleted = $progressPercentage >= 100;

        $enrollment = $this->enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        $updateData = [
            'progress_percentage' => $progressPercentage,
            'is_completed'        => $isCompleted
        ];

        if ($isCompleted && !$enrollment['is_completed']) {
            $updateData['completion_date'] = date('Y-m-d H:i:s');
        }

        $this->enrollmentModel->update($enrollment['id'], $updateData);
    }
}
