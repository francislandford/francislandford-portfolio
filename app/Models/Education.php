<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    // Eloquent's pluralizer treats "Education" as uncountable and guesses
    // the table name "education"; our actual table is "educations".
    protected $table = 'educations';

    protected $fillable = [
        'degree',
        'institution',
        'start_date',
        'end_date',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
