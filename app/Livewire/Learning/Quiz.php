<?php

namespace App\Livewire\Learning;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Quiz extends Component
{
    public Course $course;
    public Enrollment $enrollment;

    /** @var array<int,int> question_id => selected option_id */
    public array $answers = [];

    public ?QuizAttempt $result = null;

    public function mount(Course $course): void
    {
        abort_unless(Auth::check(), 403);

        $this->course = $course->load('quiz.questions.options');

        abort_unless($this->course->quiz, 404);

        $this->enrollment = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->firstOrFail();

        abort_unless($this->enrollment->hasCompletedAllLessons(), 403);
    }

    public function retake(): void
    {
        $this->result = null;
        $this->answers = [];
    }

    public function submit(): void
    {
        $quiz = $this->course->quiz;
        $questions = $quiz->questions;

        $correct = 0;

        foreach ($questions as $question) {
            $selectedOptionId = $this->answers[$question->id] ?? null;
            $correctOption = $question->options->firstWhere('is_correct', true);

            if ($selectedOptionId && $correctOption && (int) $selectedOptionId === $correctOption->id) {
                $correct++;
            }
        }

        $score = $questions->count() > 0 ? (int) round(($correct / $questions->count()) * 100) : 0;
        $passed = $score >= $quiz->passing_score;

        $this->result = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
            'answers' => $this->answers,
            'attempted_at' => now(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.learning.quiz')->layout('components.layouts.app', [
            'title' => $this->course->quiz->title.' - '.$this->course->title,
        ]);
    }
}
