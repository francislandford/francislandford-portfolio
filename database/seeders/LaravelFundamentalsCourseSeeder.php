<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class LaravelFundamentalsCourseSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::updateOrCreate(
            ['title' => 'Laravel Fundamentals'],
            [
                'excerpt' => 'A hands-on introduction to Laravel — routing, Blade, Eloquent, and migrations — for developers ready to build their first real application.',
                'description' => "This free course walks through the core building blocks of the Laravel framework: how requests are routed, how views are rendered with Blade, how Eloquent models talk to your database, and how migrations keep your schema under version control. By the end, you'll understand enough to start building your own Laravel application from scratch.",
                'body' => 'You should be comfortable with basic PHP and have a local development environment ready to go (PHP 8.2+, Composer, and MySQL or SQLite). No prior Laravel experience required.',
                'price' => null,
                'currency' => 'USD',
                'level' => 'Beginner',
                'status' => 'published',
                'order' => 1,
            ]
        );

        $lessonOneBody = <<<'TEXT'
Laravel is a PHP web application framework built around expressive, readable syntax and a set of conventions that handle the repetitive parts of building a web app — routing, database access, templating, authentication — so you can focus on the logic that's actually specific to your project.

Every request into a Laravel application starts with a route. Routes live in routes/web.php for pages a browser visits directly, and routes/api.php (when present) for JSON APIs. The simplest route just matches a URL to a closure:

```php
Route::get('/', function () {
    return view('welcome');
});
```

Most real routes point to a controller method instead of an inline closure, which keeps routes/web.php readable as an app grows:

```php
Route::get('/projects', [ProjectController::class, 'index']);
```

Routes can capture segments of the URL as parameters:

```php
Route::get('/projects/{project}', [ProjectController::class, 'show']);
```

That {project} segment gets passed into the controller method as an argument, and — thanks to route model binding — Laravel will automatically look up the matching Eloquent model for you if you type-hint it.

Finally, it's good practice to name your routes with ->name('projects.show') so you can reference them elsewhere in your app (in Blade templates, redirects, and so on) without hardcoding the URL. If the URL changes later, every reference to the named route keeps working.
TEXT;

        $lessonTwoBody = <<<'TEXT'
Blade is Laravel's templating engine. Blade files end in .blade.php and compile down to plain PHP, so there's no real performance cost to using it, and you can drop into raw PHP whenever you need to.

The most common thing you'll do in a Blade file is output a variable. Double curly braces automatically escape the value for safe HTML output:

```blade
{{ $project->title }}
```

If you genuinely need to output raw, unescaped HTML — for example, content you've already sanitized — Blade provides {!! !!}, but reach for this rarely, since it reopens the door to XSS if the content isn't trustworthy.

Blade also gives you directives for common control structures:

```blade
@if ($projects->isEmpty())
    <p>No projects yet.</p>
@else
    @foreach ($projects as $project)
        <p>{{ $project->title }}</p>
    @endforeach
@endif
```

For layouts, modern Laravel favors Blade components over the older @extends/@section approach. A layout component might look like <x-layouts.app>, wrapping the page content in a slot:

```blade
<x-layouts.app :title="$course->title">
    <h1>{{ $course->title }}</h1>
</x-layouts.app>
```

Components can be as simple as a shared header, or as involved as a full page shell with a slot for page-specific content — either way, they're the main tool Blade gives you for not repeating yourself across templates.
TEXT;

        $lessonThreeBody = <<<'TEXT'
Eloquent is Laravel's ORM (object-relational mapper) — it lets you work with your database using PHP objects and method calls instead of writing raw SQL for everyday queries.

By convention, a model named Project maps to a projects table (plural, snake_case), and Laravel assumes an auto-incrementing id primary key unless you tell it otherwise. Once a model exists, common queries read naturally:

```php
Project::all();                        // every row
Project::find(1);                      // find by primary key
Project::where('status', 'published')->get();
Project::where('status', 'published')->first();
```

Creating and updating records is just as direct:

```php
Project::create(['title' => 'New Project', 'status' => 'draft']);

$project = Project::find(1);
$project->status = 'published';
$project->save();
```

Eloquent also models relationships between tables as methods on the model. A Project that belongs to a Client, and a Client that has many Projects, would look like:

```php
// On Project
public function client() { return $this->belongsTo(Client::class); }

// On Client
public function projects() { return $this->hasMany(Project::class); }
```

With that in place, $project->client and $client->projects both work — Eloquent handles the underlying join/query for you, and can eager-load relationships (with('client')) to avoid the N+1 query problem when you're looping over many records.
TEXT;

        $lessonFourBody = <<<'TEXT'
Migrations are version control for your database schema. Instead of manually running SQL against your database (and forgetting what you changed), you write migration files that describe schema changes in PHP, and Laravel applies them in order.

Create a new migration with artisan:

```bash
php artisan make:migration create_projects_table
```

That generates a file with up() and down() methods. up() describes what to do when the migration runs; down() describes how to reverse it:

```php
public function up(): void
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('status')->default('draft');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('projects');
}
```

To apply any migrations that haven't run yet:

```bash
php artisan migrate
```

During local development, `php artisan migrate:fresh` drops every table and re-runs all migrations from scratch — useful when you want a clean slate, but destructive, so it's strictly a local/dev tool, never something you run against a database with real data.

Migrations are meant to be committed to your repository alongside the code that depends on them, so anyone who pulls your project — including a production deploy — can run `php artisan migrate` and end up with the exact same schema.
TEXT;

        $lessonFiveBody = <<<'TEXT'
Controllers group related request-handling logic into a single class, instead of stuffing every route with an inline closure. Generate one with artisan:

```bash
php artisan make:controller ProjectController
```

A typical resource controller has methods like index() (list), show() (single record), store() (create), update(), and destroy() — matching the conventional CRUD actions:

```php
class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index', [
            'projects' => Project::where('status', 'published')->get(),
        ]);
    }

    public function show(Project $project)
    {
        return view('projects.show', ['project' => $project]);
    }
}
```

Notice show() type-hints a Project directly as its parameter — that's route model binding again: Laravel resolves the {project} route segment into an actual Project instance before your method even runs, and automatically returns a 404 if no matching record exists.

For anything that accepts user input — a store() or update() method — you'll want to validate the incoming request before touching the database:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
    ]);

    Project::create($validated);

    return redirect()->route('projects.index');
}
```

That's the core loop of most Laravel features: a route points to a controller method, the method reads and validates input, talks to your Eloquent models, and returns a view or a redirect. Everything else you'll learn builds on that same shape.
TEXT;

        $lessons = [
            ['title' => 'Introduction to Laravel & Routing', 'type' => 'code', 'is_free_preview' => true, 'order' => 1, 'body' => $lessonOneBody],
            ['title' => 'The Blade Templating Engine', 'type' => 'code', 'is_free_preview' => false, 'order' => 2, 'body' => $lessonTwoBody],
            ['title' => 'Eloquent ORM Basics', 'type' => 'code', 'is_free_preview' => false, 'order' => 3, 'body' => $lessonThreeBody],
            ['title' => 'Migrations & the Database Layer', 'type' => 'code', 'is_free_preview' => false, 'order' => 4, 'body' => $lessonFourBody],
            ['title' => 'Building Your First Controller', 'type' => 'code', 'is_free_preview' => false, 'order' => 5, 'body' => $lessonFiveBody],
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
                'question' => 'Which artisan command creates a new migration file?',
                'options' => [
                    ['option' => 'php artisan make:model', 'is_correct' => false],
                    ['option' => 'php artisan make:migration', 'is_correct' => true],
                    ['option' => 'php artisan migrate:make', 'is_correct' => false],
                    ['option' => 'php artisan db:migration', 'is_correct' => false],
                ],
            ],
            [
                'question' => "What is Laravel's built-in templating engine called?",
                'options' => [
                    ['option' => 'Twig', 'is_correct' => false],
                    ['option' => 'Blade', 'is_correct' => true],
                    ['option' => 'Handlebars', 'is_correct' => false],
                    ['option' => 'Mustache', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'In Eloquent, which method retrieves every record from a table?',
                'options' => [
                    ['option' => 'Model::fetch()', 'is_correct' => false],
                    ['option' => 'Model::all()', 'is_correct' => true],
                    ['option' => 'Model::getAll()', 'is_correct' => false],
                    ['option' => 'Model::list()', 'is_correct' => false],
                ],
            ],
            [
                'question' => "Where are a Laravel application's web routes typically defined?",
                'options' => [
                    ['option' => 'routes/web.php', 'is_correct' => true],
                    ['option' => 'app/Routes.php', 'is_correct' => false],
                    ['option' => 'config/routes.php', 'is_correct' => false],
                    ['option' => 'resources/routes.php', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Which command applies any pending migrations to the database?',
                'options' => [
                    ['option' => 'php artisan migrate', 'is_correct' => true],
                    ['option' => 'php artisan db:sync', 'is_correct' => false],
                    ['option' => 'php artisan migrate:run', 'is_correct' => false],
                    ['option' => 'php artisan schema:update', 'is_correct' => false],
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
