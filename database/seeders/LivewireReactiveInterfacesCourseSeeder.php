<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class LivewireReactiveInterfacesCourseSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::updateOrCreate(
            ['title' => 'Reactive Interfaces with Livewire'],
            [
                'excerpt' => 'Build dynamic, reactive UIs entirely in PHP — live search, real-time validation, file uploads, and pagination, no separate frontend framework required.',
                'description' => "Livewire lets you build interactive interfaces — live search, forms that validate as you type, dynamic filtering — without writing a separate JavaScript application or a JSON API just to power your own frontend. This course covers the core mental model, two-way data binding, file uploads, and how to compose multiple components together, using the same patterns powering this very site.",
                'body' => "You should already know basic Laravel (routing, Blade, Eloquent). No prior JavaScript framework experience is needed — that's the point of Livewire.",
                'price' => 19,
                'currency' => 'USD',
                'level' => 'Intermediate',
                'status' => 'published',
                'order' => 4,
            ]
        );

        $lessonOneBody = <<<'TEXT'
A Livewire component is a plain PHP class paired with a Blade view. The class holds state and behavior; the view renders it. When something on the page happens — a click, a keystroke, a form submit — Livewire sends that back to the server, reruns the component's `render()` method, and swaps in the new HTML, all without a full page reload and without you writing any JavaScript.

Generate one with artisan:

```bash
php artisan make:livewire Counter
```

That creates two files. The class:

```php
class Counter extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
```

And the view:

```blade
<div>
    <h1>{{ $count }}</h1>
    <button wire:click="increment">+</button>
</div>
```

Drop it into any Blade page with `<livewire:counter />`, and it works immediately: clicking the button calls `increment()` on the server, `$count` updates, and Livewire re-renders just that component's HTML in the browser.

Every public property on the class is automatically available in the view and persists between requests as part of the component's state — you never manually pass data into the view like you would with a normal Blade template. That single idea, public properties as reactive state, is the foundation everything else in Livewire builds on.
TEXT;

        $lessonTwoBody = <<<'TEXT'
`wire:click` handles discrete actions like button presses, but most interactive UI is really about keeping a public property in sync with an input field as the user types. That's what `wire:model` does.

```blade
<input type="text" wire:model="query" />
<p>Searching for: {{ $query }}</p>
```

```php
class Search extends Component
{
    public string $query = '';
}
```

By default, `wire:model` only syncs the property when the input loses focus or the form submits — a deliberate choice to avoid sending a network request on every keystroke. For a live search box where you genuinely want every keystroke to trigger a server round-trip, add `.live`:

```blade
<input type="text" wire:model.live="query" />
```

That's expensive if the user types fast, so debouncing is usually the right move — wait until they pause before sending the request:

```blade
<input type="text" wire:model.live.debounce.300ms="query" />
```

Using the synced property to filter a query is exactly as simple as it looks:

```php
public function render()
{
    return view('livewire.search', [
        'results' => Project::where('title', 'like', "%{$this->query}%")->get(),
    ]);
}
```

Every time `$query` changes and Livewire re-renders, `render()` runs again, so the results list stays in sync automatically — there's no separate "on change" handler to wire up, because the whole component just re-evaluates from the current state.
TEXT;

        $lessonThreeBody = <<<'TEXT'
Validating a Livewire component works through the same `Validator` facade and rule syntax you already know from Form Requests — the difference is when and how often it runs.

```php
class ContactForm extends Component
{
    public string $email = '';

    protected function rules(): array
    {
        return ['email' => 'required|email'];
    }

    public function submit(): void
    {
        $this->validate();

        // ...send the message
    }
}
```

Calling `$this->validate()` inside an action method checks the current property values against the rules and throws a validation exception if they fail — Livewire catches that automatically and makes the errors available to `@error` directives in the view, exactly like a normal Blade form.

For validation that reacts as the user types rather than only on submit, add `wire:model.live` to the field and call `$this->validateOnly()` from an updated hook:

```php
public function updated($property): void
{
    $this->validateOnly($property);
}
```

That validates just the one field that changed, rather than re-validating the whole form on every keystroke — a meaningful difference once a form has more than a couple of fields.

Loading states matter just as much as validation, because every Livewire action is a network request, and users need feedback while it's in flight. `wire:loading` shows or hides an element based on whether a request is pending:

```blade
<button wire:click="submit" wire:loading.attr="disabled">
    Save
</button>
<span wire:loading>Saving...</span>
```

Scope it to a specific action with `wire:loading.attr="disabled" wire:target="submit"` so unrelated actions elsewhere on the page don't trigger the same loading state.
TEXT;

        $lessonFourBody = <<<'TEXT'
File uploads in Livewire look almost identical to a normal form field, but there's real work happening behind the scenes: the file uploads asynchronously to temporary storage the moment it's selected, before the form is even submitted.

```blade
<input type="file" wire:model="photo" />

@if ($photo)
    <img src="{{ $photo->temporaryUrl() }}" />
@endif
```

```php
use Livewire\WithFileUploads;

class ProfileForm extends Component
{
    use WithFileUploads;

    public $photo;

    public function save(): void
    {
        $this->validate(['photo' => 'image|max:2048']);

        $path = $this->photo->store('photos', 'public');
    }
}
```

The `WithFileUploads` trait is required on any component handling uploads — it's what turns the bound property into a temporary uploaded file object with a `store()` method and a `temporaryUrl()` for instant previews, instead of a plain string.

Pagination is a separate concern but comes from a similar pattern — a trait plus a small amount of Blade markup:

```php
use Livewire\WithPagination;

class ProjectList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.project-list', [
            'projects' => Project::paginate(10),
        ]);
    }
}
```

```blade
<div>
    @foreach ($projects as $project)
        {{ $project->title }}
    @endforeach

    {{ $projects->links() }}
</div>
```

Because pagination state lives in the URL query string by default, the page number survives a browser refresh and is bookmarkable — the same behavior you'd expect from a traditional paginated Blade page, just without the full page reload on each click.
TEXT;

        $lessonFiveBody = <<<'TEXT'
Real interfaces are rarely one component — a page might have a search box, a filtered list, and a modal, each with its own state. Livewire components nest inside each other exactly like Blade includes, and each one manages its own state independently.

```blade
{{-- parent component's view --}}
<div>
    <livewire:project-filters />
    <livewire:project-list />
</div>
```

The tricky part is communication: the filters component needs to tell the list component when something changed. Livewire handles this with events — a component dispatches one, and any component listening picks it up, without either needing a direct reference to the other.

```php
class ProjectFilters extends Component
{
    public function applyFilter(string $category): void
    {
        $this->dispatch('filter-changed', category: $category);
    }
}
```

```php
class ProjectList extends Component
{
    #[On('filter-changed')]
    public function refreshWithFilter(string $category): void
    {
        $this->category = $category;
    }
}
```

This decoupling matters as an app grows: `ProjectFilters` doesn't need to know `ProjectList` exists at all, just that it's announcing a change. Any number of components can listen for the same event, and you can add a new listener later without touching the component that dispatches it.

For state that genuinely belongs to a parent and needs to flow down, pass it as a normal Blade parameter instead: `<livewire:project-list :category="$category" />`. Reach for events when components are siblings coordinating a change; reach for parameters when one component owns the data and another just displays it.
TEXT;

        $lessons = [
            ['title' => 'Your First Livewire Component', 'type' => 'code', 'is_free_preview' => true, 'order' => 1, 'body' => $lessonOneBody],
            ['title' => 'Two-Way Binding with wire:model', 'type' => 'code', 'is_free_preview' => false, 'order' => 2, 'body' => $lessonTwoBody],
            ['title' => 'Real-Time Validation and Loading States', 'type' => 'code', 'is_free_preview' => false, 'order' => 3, 'body' => $lessonThreeBody],
            ['title' => 'File Uploads and Pagination', 'type' => 'code', 'is_free_preview' => false, 'order' => 4, 'body' => $lessonFourBody],
            ['title' => 'Nesting Components and Dispatching Events', 'type' => 'code', 'is_free_preview' => false, 'order' => 5, 'body' => $lessonFiveBody],
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
                'question' => 'What triggers a Livewire component to re-render?',
                'options' => [
                    ['option' => 'A full page reload only', 'is_correct' => false],
                    ['option' => 'A server round-trip triggered by an action like wire:click or wire:model', 'is_correct' => true],
                    ['option' => 'A JavaScript build step', 'is_correct' => false],
                    ['option' => 'Only when the browser tab regains focus', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Which modifier makes wire:model sync on every keystroke instead of on blur/submit?',
                'options' => [
                    ['option' => '.live', 'is_correct' => true],
                    ['option' => '.instant', 'is_correct' => false],
                    ['option' => '.sync', 'is_correct' => false],
                    ['option' => '.eager', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Which trait must a component use to handle file uploads?',
                'options' => [
                    ['option' => 'WithFiles', 'is_correct' => false],
                    ['option' => 'HasUploads', 'is_correct' => false],
                    ['option' => 'WithFileUploads', 'is_correct' => true],
                    ['option' => 'FileUploadable', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'What does calling $this->validate() inside a Livewire action method do?',
                'options' => [
                    ['option' => 'Nothing unless combined with a Form Request', 'is_correct' => false],
                    ['option' => 'Checks current property values against defined rules and surfaces errors to @error directives', 'is_correct' => true],
                    ['option' => 'Only validates on the initial page load', 'is_correct' => false],
                    ['option' => 'Requires a separate JavaScript validation library', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'How does one Livewire component notify a sibling component of a change without a direct reference?',
                'options' => [
                    ['option' => 'By dispatching an event with $this->dispatch() and listening with #[On()]', 'is_correct' => true],
                    ['option' => 'By calling the sibling\'s method directly', 'is_correct' => false],
                    ['option' => 'Global PHP variables', 'is_correct' => false],
                    ['option' => 'It is not possible in Livewire', 'is_correct' => false],
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
