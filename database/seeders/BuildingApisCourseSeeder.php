<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class BuildingApisCourseSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::updateOrCreate(
            ['title' => 'Building REST APIs with Laravel'],
            [
                'excerpt' => 'Design, secure, and ship a real JSON API — resourceful routes, API Resources, Sanctum authentication, and versioning that won\'t break your mobile app.',
                'description' => "Most Laravel apps eventually need to talk to something other than a browser — a mobile app, a frontend SPA, another service. This course walks through building that API properly: resourceful routing, shaping responses with API Resources instead of raw models, authenticating requests with Sanctum, and handling validation and rate limiting the way production APIs actually need to.",
                'body' => "You should already be comfortable with Eloquent models, migrations, and basic routing. This course assumes you can build a standard Laravel CRUD feature and want to expose it as JSON instead of (or alongside) Blade views.",
                'price' => 19,
                'currency' => 'USD',
                'level' => 'Intermediate',
                'status' => 'published',
                'order' => 3,
            ]
        );

        $lessonOneBody = <<<'TEXT'
A resourceful API maps HTTP verbs and URLs onto a predictable set of actions, so anyone consuming it can guess the shape without reading your docs. Laravel gives you this for free with `apiResource`:

```php
// routes/api.php
Route::apiResource('projects', ProjectController::class);
```

That single line registers five routes:

```
GET    /api/projects           index
POST   /api/projects           store
GET    /api/projects/{project} show
PUT    /api/projects/{project} update
DELETE /api/projects/{project} destroy
```

Notice there's no `create` or `edit` route — those exist in the web resource controller to return HTML forms, but an API has no forms to render, so `apiResource` skips them. Generate the matching controller with the `--api` flag so Laravel stubs out exactly these five methods instead of all seven:

```bash
php artisan make:controller Api/ProjectController --api --model=Project
```

Route model binding works identically to web routes — type-hint the model in the method signature and Laravel resolves `{project}` to an actual `Project` instance, returning a 404 automatically if it doesn't exist:

```php
public function show(Project $project)
{
    return $project;
}
```

Returning a model directly, like above, works because Eloquent models implement `Arrayable` and `Jsonable` — Laravel serializes them to JSON automatically. That's fine for a quick prototype, but it means every public column and loaded relationship goes straight into the response, including things like timestamps or foreign keys you might not want exposed. That's the exact problem the next lesson solves.
TEXT;

        $lessonTwoBody = <<<'TEXT'
Returning a raw Eloquent model works, but it gives you no control over the shape of the response — every attribute goes out, in whatever format the database stores it. API Resources fix that by putting a transformation layer between your models and your JSON output.

Generate one with artisan:

```bash
php artisan make:resource ProjectResource
```

The generated class has a `toArray()` method where you explicitly define what goes into the response:

```php
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'cover_image_url' => $this->cover_image ? Storage::url($this->cover_image) : null,
            'published_at' => $this->created_at->toIso8601String(),
        ];
    }
}
```

Use it in a controller by wrapping the model or collection:

```php
public function show(Project $project)
{
    return new ProjectResource($project);
}

public function index()
{
    return ProjectResource::collection(Project::published()->paginate(15));
}
```

`ProjectResource::collection()` on a paginated query automatically includes pagination metadata (`links`, `meta`) alongside the transformed `data` array, so API consumers get page numbers and total counts without any extra work on your end.

This layer is also where you handle relationships deliberately: use `$this->whenLoaded('categories')` to include a relationship only when it was actually eager loaded, so you never accidentally trigger an N+1 query from inside a resource.
TEXT;

        $lessonThreeBody = <<<'TEXT'
Sanctum is Laravel's official package for API authentication, and it covers two distinct cases: token authentication for mobile apps and third-party clients, and cookie-based authentication for a first-party SPA. For a typical mobile app or external client, you'll use tokens.

After installing Sanctum and adding its migration, give the `User` model the `HasApiTokens` trait:

```php
class User extends Authenticatable
{
    use HasApiTokens;
}
```

A login endpoint validates credentials and issues a token, which the client stores and sends back on every subsequent request:

```php
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Invalid credentials.'],
        ]);
    }

    return response()->json([
        'token' => $user->createToken('mobile')->plainTextToken,
    ]);
}
```

Protect any route that needs an authenticated user with the `auth:sanctum` middleware:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);
});
```

The client sends the token back as a bearer header on every request: `Authorization: Bearer {token}`. Inside a protected route, `$request->user()` returns the authenticated `User` model, resolved automatically from that token — no manual lookup required.

Tokens can also be scoped to specific abilities (`createToken('mobile', ['projects:read'])`), which is worth using once your API has more than one type of client with different permission needs.
TEXT;

        $lessonFourBody = <<<'TEXT'
An API that trusts its input, or fails silently when something goes wrong, is a liability. Laravel's validation and exception handling both work the same way for APIs as they do for web routes — the difference is entirely in what gets returned to the client.

Form Requests keep validation out of your controller and give you a single place to define the rules:

```php
class StoreProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ];
    }
}
```

When validation fails on a request expecting JSON (which Laravel detects from the `Accept` header), Laravel automatically returns a `422 Unprocessable Entity` response with a structured `errors` object — you don't need to catch anything yourself:

```json
{
  "message": "The title field is required.",
  "errors": { "title": ["The title field is required."] }
}
```

Rate limiting protects your API from being hammered, whether by a bug in a client or something malicious. Laravel ships with a sensible default, applied via middleware:

```php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::apiResource('projects', ProjectController::class);
});
```

That limits each authenticated user (or IP, for guests) to 60 requests per minute, and returns a `429 Too Many Requests` response once they exceed it — with `X-RateLimit-Remaining` headers on every response so well-behaved clients can back off before hitting the wall.

For anything not covered automatically — a third-party API call that failed, a business rule violation — throw a specific exception and handle it in `bootstrap/app.php`'s exception handling so every error path returns the same consistent JSON shape.
TEXT;

        $lessonFiveBody = <<<'TEXT'
The moment your API has a second consumer — a mobile app update that's slower to roll out than your backend, or an external partner integration — you can't just change a response shape and deploy. Versioning gives you a way to evolve the API without breaking clients still on the old contract.

The simplest and most common approach is a version prefix in the URL:

```php
// routes/api.php
Route::prefix('v1')->group(base_path('routes/api_v1.php'));
Route::prefix('v2')->group(base_path('routes/api_v2.php'));
```

Each version gets its own route file and, where the shape actually changed, its own controllers and API Resources. Where nothing changed between versions, both route files can point at the same controller — you're only duplicating what's actually different.

Documentation matters just as much as versioning, because an undocumented API is one every consumer has to reverse-engineer from your source code. At minimum, generate an OpenAPI/Swagger spec (packages like `dedoc/scramble` can generate one directly from your Laravel routes and Form Requests) so consumers get accurate, browsable docs that don't drift out of sync with the actual code.

A practical rule for when to bump the version: additive changes (new optional fields, new endpoints) don't need a new version — clients ignoring fields they don't recognize is expected. Anything that removes a field, changes a field's type, or changes existing behavior is a breaking change and needs `v2`.

Keep old versions alive with a clear deprecation window and a `Sunset` response header, so consumers have real notice before you eventually retire them.
TEXT;

        $lessons = [
            ['title' => 'Designing Resourceful API Routes', 'type' => 'code', 'is_free_preview' => true, 'order' => 1, 'body' => $lessonOneBody],
            ['title' => 'Shaping Responses with API Resources', 'type' => 'code', 'is_free_preview' => false, 'order' => 2, 'body' => $lessonTwoBody],
            ['title' => 'Authenticating APIs with Sanctum', 'type' => 'code', 'is_free_preview' => false, 'order' => 3, 'body' => $lessonThreeBody],
            ['title' => 'Validation, Errors, and Rate Limiting', 'type' => 'code', 'is_free_preview' => false, 'order' => 4, 'body' => $lessonFourBody],
            ['title' => 'Versioning and Documenting Your API', 'type' => 'code', 'is_free_preview' => false, 'order' => 5, 'body' => $lessonFiveBody],
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
                'question' => 'Which artisan route macro registers the five standard API routes (no create/edit)?',
                'options' => [
                    ['option' => 'Route::resource()', 'is_correct' => false],
                    ['option' => 'Route::apiResource()', 'is_correct' => true],
                    ['option' => 'Route::crud()', 'is_correct' => false],
                    ['option' => 'Route::json()', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'What is the main purpose of an API Resource class?',
                'options' => [
                    ['option' => 'To define database migrations', 'is_correct' => false],
                    ['option' => 'To control exactly what shape a model is serialized to in a JSON response', 'is_correct' => true],
                    ['option' => 'To register API routes', 'is_correct' => false],
                    ['option' => 'To handle authentication', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Which package is Laravel\'s official solution for API token authentication?',
                'options' => [
                    ['option' => 'Passport only', 'is_correct' => false],
                    ['option' => 'Sanctum', 'is_correct' => true],
                    ['option' => 'Socialite', 'is_correct' => false],
                    ['option' => 'Fortify', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'What HTTP status code does Laravel return automatically for a failed validation request expecting JSON?',
                'options' => [
                    ['option' => '400', 'is_correct' => false],
                    ['option' => '404', 'is_correct' => false],
                    ['option' => '422', 'is_correct' => true],
                    ['option' => '500', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'What kind of API change requires bumping to a new version like v2?',
                'options' => [
                    ['option' => 'Adding a new optional field to a response', 'is_correct' => false],
                    ['option' => 'Adding a brand-new endpoint', 'is_correct' => false],
                    ['option' => 'Removing or changing the type of an existing field', 'is_correct' => true],
                    ['option' => 'Fixing a typo in a field\'s value', 'is_correct' => false],
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
