<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class EloquentRelationshipsCourseSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::updateOrCreate(
            ['title' => 'Eloquent Relationships & Query Mastery'],
            [
                'excerpt' => 'Go beyond basic CRUD — model real relationships, eliminate N+1 queries, and write Eloquent code that stays fast as your data grows.',
                'description' => "This course picks up where Laravel Fundamentals leaves off. You'll model one-to-many and many-to-many relationships, learn why eager loading matters and how to spot an N+1 problem before it ships, build reusable query scopes, and shape your data with accessors, mutators, and casts.",
                'body' => "You should be comfortable with basic Eloquent (creating, reading, updating models) and have completed Laravel Fundamentals or equivalent experience. A local Laravel app with a database is all you need to follow along.",
                'price' => null,
                'currency' => 'USD',
                'level' => 'Intermediate',
                'status' => 'published',
                'order' => 2,
            ]
        );

        $lessonOneBody = <<<'TEXT'
Relationships are how Eloquent models talk to each other, and they mirror the foreign keys already sitting in your database. The three you'll reach for constantly are `hasOne`, `hasMany`, and `belongsTo`.

If a `Course` has many `Lesson` records, the relationship lives on the "one" side as `hasMany`:

```php
class Course extends Model
{
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
}
```

On the other side, each `Lesson` belongs to exactly one `Course`, so that relationship is `belongsTo`:

```php
class Lesson extends Model
{
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
```

Once both sides are defined, you call the relationship like a property, not a method — Eloquent handles the query for you and caches the result on the model instance:

```php
$course = Course::find(1);
foreach ($course->lessons as $lesson) {
    echo $lesson->title;
}
```

`hasOne` works exactly like `hasMany` but expects a single related record instead of a collection — useful for something like a `Course` having one `Quiz`. Eloquent infers the foreign key (`course_id`) and local key (`id`) from convention, but you can always pass them explicitly as extra arguments if your column names don't match the default.

Getting the direction right is the part beginners trip over: the relationship method goes on the model that *has* the others (`hasMany`), and the foreign key column lives on the model that *belongs to* the other (`belongsTo`). If you remember which table actually holds the foreign key, the direction follows naturally.
TEXT;

        $lessonTwoBody = <<<'TEXT'
Not every relationship is one-to-many. When two models can each relate to many of the other — think `Project` and `Skill`, where a project uses several skills and a skill appears on several projects — you need a pivot table and `belongsToMany`.

The migration creates a table named after both models in alphabetical, singular, underscore-joined order (`project_skill`), with two foreign key columns:

```php
Schema::create('project_skill', function (Blueprint $table) {
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
});
```

Both models declare the relationship the same way:

```php
class Project extends Model
{
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }
}
```

Attaching and detaching records doesn't touch the parent models at all — it just manages rows in the pivot table:

```php
$project->skills()->attach($skillId);
$project->skills()->detach($skillId);
$project->skills()->sync([$id1, $id2, $id3]); // replaces the full set
```

`sync()` is the one you'll use most often in practice: hand it the full list of IDs that should be attached, and Eloquent works out which rows to add and remove to match.

If the pivot table needs extra data beyond the two foreign keys — an `order` column, a `role`, a timestamp — call `->withPivot('order')` on the relationship definition, and that column becomes available on each related model as `$skill->pivot->order`.
TEXT;

        $lessonThreeBody = <<<'TEXT'
The N+1 problem is the single most common Eloquent performance bug, and it's invisible until you look at your query log. Here's what it looks like:

```php
$courses = Course::all(); // 1 query

foreach ($courses as $course) {
    echo $course->lessons->count(); // 1 query PER course
}
```

If you have 20 courses, that's 21 queries to render one page — one to fetch the courses, and one more *for each course* the first time you touch `->lessons`. Eloquent doesn't know ahead of time that you're going to need every course's lessons, so it fetches them lazily, one relationship at a time.

Eager loading fixes this by telling Eloquent up front which relationships you'll need, so it can fetch them in a second, batched query instead of one query per row:

```php
$courses = Course::with('lessons')->get(); // 2 queries total
```

You can eager load nested relationships with dot notation, and multiple relationships at once:

```php
$courses = Course::with(['lessons', 'quiz.questions.options'])->get();
```

To catch N+1 problems before they reach production, Laravel can throw an exception the moment lazy loading happens, which is worth turning on in local development:

```php
// In a service provider's boot() method
Model::preventLazyLoading(! app()->isProduction());
```

The rule of thumb: any time you're about to loop over a collection and access a relationship inside that loop, ask whether you eager loaded it first. If you didn't, you've probably just written an N+1 query.
TEXT;

        $lessonFourBody = <<<'TEXT'
Query scopes let you package up a reusable "where" clause as a method, so instead of repeating the same filter everywhere, you give it a name. A local scope is just a method prefixed with `scope`:

```php
class Post extends Model
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }
}
```

Call it by dropping the `scope` prefix and chaining it like any other query method:

```php
$posts = Post::published()->orderByDesc('published_at')->get();
```

Scopes compose, which is where they really pay off. You can chain several together, and each one only adds its own condition:

```php
Post::published()->whereHas('categories', fn ($q) => $q->where('slug', 'laravel'))->get();
```

For a filter that needs a parameter, just accept extra arguments after `Builder $query`:

```php
public function scopeByAuthor(Builder $query, int $authorId): Builder
{
    return $query->where('author_id', $authorId);
}

Post::published()->byAuthor($request->user()->id)->get();
```

If a scope applies to *every* query against a model — like always excluding soft-deleted rows, or always filtering to the current tenant — a global scope is the better tool, applied automatically without needing to call it. But for anything conditional, local scopes keep query logic readable and out of your controllers.
TEXT;

        $lessonFiveBody = <<<'TEXT'
Accessors and mutators let you transform an attribute every time it's read or written, without changing what's actually stored in the database. Since Laravel 9, both directions live in a single method using the `Attribute` class:

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Model
{
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }
}
```

Now `$user->name = 'FRANCIS'` stores `francis`, and reading `$user->name` back returns `Francis` — the transformation happens automatically on every access, so the rest of your codebase never has to think about it.

Casts handle a related but simpler problem: converting a column's type automatically. They're declared in the `$casts` property instead of a method:

```php
protected $casts = [
    'is_active' => 'boolean',
    'price' => 'decimal:2',
    'metadata' => 'array',
    'published_at' => 'datetime',
];
```

An `array` (or `json`) cast is especially useful — it means you can store structured data in a single JSON column and Eloquent will decode it to a real PHP array on read and encode it back to JSON on save, no manual `json_encode`/`json_decode` calls anywhere in your application code.

Between the two: reach for a cast when you just need type conversion, and reach for an accessor/mutator when you need actual logic — formatting, combining fields, or validating on write.
TEXT;

        $lessons = [
            ['title' => 'Defining hasMany and belongsTo Relationships', 'type' => 'code', 'is_free_preview' => true, 'order' => 1, 'body' => $lessonOneBody],
            ['title' => 'Many-to-Many Relationships with belongsToMany', 'type' => 'code', 'is_free_preview' => false, 'order' => 2, 'body' => $lessonTwoBody],
            ['title' => 'Eager Loading and the N+1 Problem', 'type' => 'code', 'is_free_preview' => false, 'order' => 3, 'body' => $lessonThreeBody],
            ['title' => 'Query Scopes for Reusable Filters', 'type' => 'code', 'is_free_preview' => false, 'order' => 4, 'body' => $lessonFourBody],
            ['title' => 'Accessors, Mutators, and Casts', 'type' => 'code', 'is_free_preview' => false, 'order' => 5, 'body' => $lessonFiveBody],
        ];

        foreach ($lessons as $lessonData) {
            Lesson::updateOrCreate(
                ['course_id' => $course->id, 'title' => $lessonData['title']],
                array_merge($lessonData, ['course_id' => $course->id])
            );
        }

        $quiz = Quiz::updateOrCreate(
            ['course_id' => $course->id],
            ['title' => 'Final Quiz', 'passing_score' => 70]
        );

        $questions = [
            [
                'question' => 'On which model should a hasMany relationship be defined?',
                'options' => [
                    ['option' => 'The model that holds the foreign key', 'is_correct' => false],
                    ['option' => 'The model being related to many others (the "one" side)', 'is_correct' => true],
                    ['option' => 'Either model — it does not matter', 'is_correct' => false],
                    ['option' => 'Only the pivot table model', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Which Eloquent method replaces a pivot table\'s full set of related IDs in one call?',
                'options' => [
                    ['option' => 'attach()', 'is_correct' => false],
                    ['option' => 'detach()', 'is_correct' => false],
                    ['option' => 'sync()', 'is_correct' => true],
                    ['option' => 'toggle()', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'What is the N+1 problem?',
                'options' => [
                    ['option' => 'A migration naming convention', 'is_correct' => false],
                    ['option' => 'Running one query per row when looping over lazily-loaded relationships', 'is_correct' => true],
                    ['option' => 'An error thrown when a model has no primary key', 'is_correct' => false],
                    ['option' => 'A limit on how many relationships a model can have', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Which method eager loads a relationship to avoid N+1 queries?',
                'options' => [
                    ['option' => 'Model::with()', 'is_correct' => true],
                    ['option' => 'Model::load()', 'is_correct' => false],
                    ['option' => 'Model::eager()', 'is_correct' => false],
                    ['option' => 'Model::fetch()', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'What is the correct way to prefix a local query scope method for a "published" scope?',
                'options' => [
                    ['option' => 'publishedScope()', 'is_correct' => false],
                    ['option' => 'withPublished()', 'is_correct' => false],
                    ['option' => 'scopePublished()', 'is_correct' => true],
                    ['option' => 'filterPublished()', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $index => $questionData) {
            $question = QuizQuestion::updateOrCreate(
                ['quiz_id' => $quiz->id, 'question' => $questionData['question']],
                ['order' => $index + 1]
            );

            foreach ($questionData['options'] as $optionIndex => $optionData) {
                QuizOption::updateOrCreate(
                    ['quiz_question_id' => $question->id, 'option' => $optionData['option']],
                    ['is_correct' => $optionData['is_correct'], 'order' => $optionIndex + 1]
                );
            }
        }
    }
}
