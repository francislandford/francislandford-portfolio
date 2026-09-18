<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Resources\Courses\CourseResource;
use App\Filament\Resources\Quizzes\QuizResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manageQuiz')
                ->label(fn () => $this->record->quiz ? 'Manage Quiz' : 'Add Quiz')
                ->icon(Heroicon::OutlinedQuestionMarkCircle)
                ->color('gray')
                ->url(fn () => $this->record->quiz
                    ? QuizResource::getUrl('edit', ['record' => $this->record->quiz])
                    : QuizResource::getUrl('create')),
            DeleteAction::make(),
        ];
    }
}
