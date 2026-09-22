<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class TestingDeployingLaravelCourseSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::updateOrCreate(
            ['title' => 'Testing & Deploying Laravel Applications'],
            [
                'excerpt' => 'Write tests that actually catch bugs, then ship your app to production with zero-downtime deploys, queues, and a task scheduler that keeps running.',
                'description' => "Building the feature is half the job — this course covers the other half. You'll write feature and unit tests with Pest, learn what to mock and what to let run for real, then take an app to production: environment configuration, zero-downtime deployment, background queues, and the task scheduler that keeps recurring jobs running without a cron entry per task.",
                'body' => "This is an advanced course — you should be comfortable building complete Laravel features (routes, controllers, Eloquent models, migrations) already. We won't re-cover the basics here.",
                'price' => 29,
                'currency' => 'USD',
                'level' => 'Advanced',
                'status' => 'published',
                'order' => 5,
            ]
        );

        $lessonOneBody = <<<'TEXT'
Pest is the testing framework most new Laravel projects reach for — it's built on PHPUnit underneath, so anything you learn here transfers, but the syntax is function-based and noticeably less ceremonial.

A feature test exercises your application the way a real request would: hitting a route, checking the response.

```php
// tests/Feature/ProjectsTest.php
test('the projects page lists published projects', function () {
    $project = Project::factory()->create(['status' => 'published']);

    $response = $this->get('/projects');

    $response->assertOk();
    $response->assertSee($project->title);
});
```

Run it with:

```bash
php artisan test
```

`assertOk()` checks for a 200 status code, and `assertSee()` checks that specific text appears in the rendered HTML — between the two, you've verified both that the route works and that it's actually showing real data, not just returning an empty page.

Every test in Laravel runs against a separate testing database by default, and (with the `RefreshDatabase` trait) each test starts from a clean, migrated database — so tests never leak state into each other or depend on the order they run in:

```php
uses(RefreshDatabase::class);
```

The habit worth building early: write the test for the behavior you're about to add *before* you add it, or at minimum immediately after. A test suite that only gets written once, long after the features already work, tends to test what the code already does rather than what it's actually supposed to do — which misses the bugs that matter.
TEXT;

        $lessonTwoBody = <<<'TEXT'
Testing anything that touches Eloquent means generating realistic fake data, and that's exactly what model factories are for. Every model in a modern Laravel app gets one by convention:

```php
// database/factories/ProjectFactory.php
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'slug' => fake()->slug(),
            'status' => 'draft',
        ];
    }
}
```

`fake()` gives you a Faker instance for generating realistic-looking names, sentences, emails, and dates — enough variety that your tests aren't all working against the exact same hardcoded row.

Factories support states for common variations, so tests can be explicit about exactly what they need:

```php
public function published(): static
{
    return $this->state(['status' => 'published']);
}
```

```php
$project = Project::factory()->published()->create();
```

For testing a model in isolation, without a real database write, `make()` builds the model in memory without persisting it — useful for testing validation logic or accessors where you don't actually need a row in the database:

```php
$project = Project::factory()->make();
```

When a test needs to check the database directly rather than just the response, Laravel's assertions read naturally:

```php
$this->assertDatabaseHas('projects', ['title' => $project->title]);
$this->assertDatabaseCount('projects', 1);
```

Between factories for setup and database assertions for verification, most feature tests end up reading like a short story: create the data, perform the action, assert the result — no manual SQL required anywhere in the test itself.
TEXT;

        $lessonThreeBody = <<<'TEXT'
Some things shouldn't actually happen during a test run — you don't want a test suite that really sends emails, really calls a payment gateway, or really dispatches a job to a queue worker. Laravel's fakes swap those side effects out for an in-memory record you can assert against instead.

```php
Mail::fake();

// ...perform the action that should send an email

Mail::assertSent(ContactMessageReceived::class, function ($mail) use ($contact) {
    return $mail->contactMessage->id === $contact->id;
});
```

`Mail::fake()` intercepts every outgoing mail for the rest of the test — nothing actually gets sent, but you can assert exactly what *would* have been sent and to whom. The same pattern applies to `Queue::fake()`, `Notification::fake()`, `Event::fake()`, and `Http::fake()` for outgoing API calls.

Queued jobs deserve their own test, separate from the feature test that dispatches them — check that the job does what it claims, in isolation:

```php
test('the sync job pulls remote records', function () {
    Http::fake([
        'api.example.com/*' => Http::response(['records' => []], 200),
    ]);

    (new SyncRemoteRecords)->handle();

    Http::assertSent(fn ($request) => $request->url() === 'https://api.example.com/records');
});
```

The rule that keeps a test suite fast and reliable: fake anything that crosses a real network boundary — mail, HTTP calls, payment gateways, SMS — and let everything that's purely your own application logic (models, Eloquent queries, validation) run for real against the test database. Faking too much starts testing your mocks instead of your code; faking too little makes the suite slow and flaky.
TEXT;

        $lessonFourBody = <<<'TEXT'
Getting code onto a production server is the easy part; doing it without an outage for the people already using the app is where deployment actually gets interesting.

The `.env` file is the first thing to get right — it should never be committed to git, and production needs its own copy with real credentials, `APP_ENV=production`, and `APP_DEBUG=false` (a stack trace with database credentials in it is not something the public internet should ever see):

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
```

A standard deploy sequence looks roughly like this, whether run by hand or a CI/CD pipeline:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

`--force` is required for migrations in production because Laravel normally prompts for confirmation before running destructive commands against a non-local environment — in an automated deploy there's no one there to answer the prompt.

Caching config, routes, and views isn't optional for a production app — it's the difference between Laravel re-parsing every config file and route definition on every single request versus reading one pre-compiled PHP array. Skipping it works fine in local development where you want those changes picked up instantly, but it leaves real performance on the table in production.

Zero-downtime deployment means the old version keeps serving requests until the new version is fully ready — typically by deploying to a new release directory and atomically swapping a symlink once the new code is deployed, migrated, and cached, so there's never a moment where the app is half-updated.
TEXT;

        $lessonFiveBody = <<<'TEXT'
Not everything a web request does needs to happen before the response goes back to the user. Sending an email, generating a report, calling a slow third-party API — anything that doesn't need to block the response is a candidate for a queued job.

```php
class SendWelcomeEmail implements ShouldQueue
{
    public function __construct(public User $user) {}

    public function handle(): void
    {
        Mail::to($this->user)->send(new WelcomeMail($this->user));
    }
}
```

Dispatching it is one line, and returns immediately without waiting for the email to actually send:

```php
SendWelcomeEmail::dispatch($user);
```

A queue worker process picks jobs up off the queue and runs them in the background:

```bash
php artisan queue:work
```

In production, that worker needs to stay running permanently, which is a job for a process monitor like Supervisor — configured to restart the worker automatically if it crashes, since a worker that dies silently means every subsequent job just sits in the queue unprocessed.

The scheduler solves a related but different problem: recurring tasks, like sending a weekly digest or cleaning up expired records. Instead of one server cron entry per task, Laravel defines every scheduled task in code and needs exactly one cron entry, ever:

```php
// routes/console.php
Schedule::command('digest:send')->weekly();
Schedule::call(fn () => Cleanup::run())->daily();
```

```bash
* * * * * php /path/to/artisan schedule:run
```

That single cron entry runs every minute and lets Laravel decide what actually needs to execute — new scheduled tasks just get added in code, with no server configuration changes required to deploy them.
TEXT;

        $lessons = [
            ['title' => 'Writing Your First Feature Test with Pest', 'type' => 'code', 'is_free_preview' => true, 'order' => 1, 'body' => $lessonOneBody],
            ['title' => 'Testing Models, Factories, and the Database', 'type' => 'code', 'is_free_preview' => false, 'order' => 2, 'body' => $lessonTwoBody],
            ['title' => 'Mocking, Fakes, and Testing Queued Jobs', 'type' => 'code', 'is_free_preview' => false, 'order' => 3, 'body' => $lessonThreeBody],
            ['title' => 'Deploying to Production Without Downtime', 'type' => 'code', 'is_free_preview' => false, 'order' => 4, 'body' => $lessonFourBody],
            ['title' => 'Queues, Scheduling, and Keeping Things Running', 'type' => 'code', 'is_free_preview' => false, 'order' => 5, 'body' => $lessonFiveBody],
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
                'question' => 'Which trait ensures each test starts from a clean, migrated database?',
                'options' => [
                    ['option' => 'CleanDatabase', 'is_correct' => false],
                    ['option' => 'RefreshDatabase', 'is_correct' => true],
                    ['option' => 'FreshMigrations', 'is_correct' => false],
                    ['option' => 'ResetState', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'What is the difference between Model::factory()->create() and Model::factory()->make()?',
                'options' => [
                    ['option' => 'There is no difference', 'is_correct' => false],
                    ['option' => 'create() persists to the database; make() builds the model in memory only', 'is_correct' => true],
                    ['option' => 'make() is only for testing relationships', 'is_correct' => false],
                    ['option' => 'create() is deprecated in favor of make()', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Why use Mail::fake() in a test instead of letting mail actually send?',
                'options' => [
                    ['option' => 'It makes the test slower but more accurate', 'is_correct' => false],
                    ['option' => 'It prevents real emails from sending while letting you assert what would have been sent', 'is_correct' => true],
                    ['option' => 'It is required for all Laravel tests', 'is_correct' => false],
                    ['option' => 'It replaces the need for a mail configuration entirely', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Why must config, route, and view caches be rebuilt during a production deploy?',
                'options' => [
                    ['option' => 'They are not required — only a convenience in local development', 'is_correct' => false],
                    ['option' => 'To avoid Laravel re-parsing config/routes/views on every request, which is a real performance cost in production', 'is_correct' => true],
                    ['option' => 'They are required only when using Livewire', 'is_correct' => false],
                    ['option' => 'To satisfy a security requirement', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'How many cron entries does the Laravel scheduler need, regardless of how many scheduled tasks are defined in code?',
                'options' => [
                    ['option' => 'One per scheduled task', 'is_correct' => false],
                    ['option' => 'Exactly one, running schedule:run every minute', 'is_correct' => true],
                    ['option' => 'None — the scheduler runs without cron', 'is_correct' => false],
                    ['option' => 'One per queue worker', 'is_correct' => false],
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
