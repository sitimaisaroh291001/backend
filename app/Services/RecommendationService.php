<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Progress;

class RecommendationService
{
    public function getRecommendations($userId)
    {
        $progresses = Progress::where('user_id', $userId)->with('lesson')->get();

        $recommended = [];

        if ($progresses->isEmpty()) {
            // No progress data, recommend first lessons from each classroom
            $lessons = Lesson::with('classroom')->take(5)->get();
            foreach ($lessons as $lesson) {
                $recommended[] = [
                    'lesson_id' => $lesson->id,
                    'title' => $lesson->title,
                    'reason' => 'Rekomendasi awal untuk memulai pembelajaran'
                ];
            }
        } else {
            // Find lessons with low progress (< 50%)
            $lowProgressLessons = $progresses->filter(function ($progress) {
                return $progress->progress_percentage < 50;
            });

            if ($lowProgressLessons->isNotEmpty()) {
                foreach ($lowProgressLessons->take(3) as $progress) {
                    $recommended[] = [
                        'lesson_id' => $progress->lesson->id,
                        'title' => $progress->lesson->title,
                        'reason' => 'Progress rendah, perlu ditingkatkan'
                    ];
                }
            }

            // Find incomplete lessons
            $incompleteLessons = $progresses->filter(function ($progress) {
                return $progress->progress_percentage < 100;
            });

            if ($incompleteLessons->isNotEmpty()) {
                foreach ($incompleteLessons->take(2) as $progress) {
                    $recommended[] = [
                        'lesson_id' => $progress->lesson->id,
                        'title' => $progress->lesson->title,
                        'reason' => 'Belum selesai, lanjutkan pembelajaran'
                    ];
                }
            }

            // If still need more, recommend new lessons from same classrooms
            if (count($recommended) < 3) {
                $classroomIds = $progresses->pluck('lesson.classroom_id')->unique();
                $newLessons = Lesson::whereIn('classroom_id', $classroomIds)
                    ->whereNotIn('id', $progresses->pluck('lesson_id'))
                    ->with('classroom')
                    ->take(3 - count($recommended))
                    ->get();

                foreach ($newLessons as $lesson) {
                    $recommended[] = [
                        'lesson_id' => $lesson->id,
                        'title' => $lesson->title,
                        'reason' => 'Materi baru dari kelas yang sama'
                    ];
                }
            }
        }

        return [
            'user_id' => $userId,
            'recommended' => array_slice($recommended, 0, 5) // Limit to 5 recommendations
        ];
    }
}
