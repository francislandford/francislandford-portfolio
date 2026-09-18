<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'completed' => 'Completed'])
                    ->default('active')
                    ->required(),
                DateTimePicker::make('enrolled_at')
                    ->required(),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
