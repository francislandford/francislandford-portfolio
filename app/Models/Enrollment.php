<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function progressPercentage(): int
    {
        $totalLessons = $this->course->lessons()->count();

        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->lessonProgress()->count();

        return (int) round(($completedLessons / $totalLessons) * 100);
    }

    public function hasCompletedAllLessons(): bool
    {
        $totalLessons = $this->course->lessons()->count();

        return $totalLessons > 0 && $this->lessonProgress()->count() >= $totalLessons;
    }

    public function hasCompletedLesson(Lesson $lesson): bool
    {
        return $this->lessonProgress()->where('lesson_id', $lesson->id)->exists();
    }

    public function isEligibleForCertificate(): bool
    {
        if (! $this->hasCompletedAllLessons()) {
            return false;
        }

        $quiz = $this->course->quiz;

        if (! $quiz) {
            return true;
        }

        return QuizAttempt::where('user_id', $this->user_id)
            ->where('quiz_id', $quiz->id)
            ->where('passed', true)
            ->exists();
    }
}
