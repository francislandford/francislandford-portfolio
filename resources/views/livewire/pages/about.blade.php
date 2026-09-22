@php
    use App\Models\Setting;
    use Illuminate\Support\Facades\Storage;

    $categoryLabels = [
        'award' => 'Award',
        'milestone' => 'Milestone',
        'recognition' => 'Recognition',
        'speaking' => 'Speaking',
        'community' => 'Community',
    ];
@endphp

<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">About Me</p>
            <h1 class="mt-4 text-4xl font-bold text-white sm:text-5xl">{{ Setting::get('name') }}</h1>
            <p class="mt-4 text-lg text-slate-300">{{ Setting::get('tagline') }}</p>
            <a href="{{ route('resume') }}" target="_blank" class="mt-8 inline-block rounded-full border border-slate-700 px-6 py-3 text-sm font-semibold text-white transition hover:border-amber-500 hover:text-amber-400">
                View / Print Resume
            </a>
        </div>
    </section>

    {{-- Experience timeline --}}
    <section class="bg-white py-20 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl px-6">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Career</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Experience</h2>

            @if($experiences->isEmpty())
                <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">Experience details coming soon.</p>
            @else
                <div class="mt-10 space-y-10 border-l border-slate-200 pl-8 dark:border-slate-800">
                    @foreach($experiences as $experience)
                        <div class="relative">
                            <span class="absolute -left-[2.15rem] top-1.5 h-3 w-3 rounded-full bg-amber-500"></span>
                            <p class="font-mono text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                                {{ $experience->start_date->format('M Y') }} —
                                {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                            </p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ $experience->role_title }}</h3>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">
                                {{ $experience->company }}
                                @if($experience->location)
                                    <span class="text-slate-400 dark:text-slate-500">· {{ $experience->location }}</span>
                                @endif
                            </p>
                            @if($experience->description)
                                <ul class="mt-2 list-disc space-y-1 pl-4 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                                    @foreach(explode("\n", $experience->description) as $line)
                                        <li>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Skills --}}
    @if($skills->isNotEmpty())
        <section class="bg-slate-50 py-20 dark:bg-slate-900/40">
            <div class="mx-auto max-w-3xl px-6">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Toolbox</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Skills</h2>
                <div class="mt-8 flex flex-wrap gap-3">
                    @foreach($skills as $skill)
                        <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                            {{ $skill->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Education --}}
    @if($educations->isNotEmpty())
        <section class="bg-white py-20 dark:bg-slate-950">
            <div class="mx-auto max-w-3xl px-6">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Academics</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Education</h2>
                <div class="mt-10 space-y-8 border-l border-slate-200 pl-8 dark:border-slate-800">
                    @foreach($educations as $education)
                        <div class="relative">
                            <span class="absolute -left-[2.15rem] top-1.5 h-3 w-3 rounded-full bg-amber-500"></span>
                            <p class="font-mono text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                                @if($education->start_date && $education->end_date && $education->start_date->format('Y') !== $education->end_date->format('Y'))
                                    {{ $education->start_date->format('Y') }} — {{ $education->end_date->format('Y') }}
                                @elseif($education->end_date)
                                    {{ $education->end_date->format('Y') }}
                                @endif
                            </p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ $education->degree }}</h3>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $education->institution }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Certifications --}}
    @if($certifications->isNotEmpty())
        <section class="bg-slate-50 py-20 dark:bg-slate-900/40">
            <div class="mx-auto max-w-3xl px-6">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Credentials</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Certifications</h2>
                <div class="mt-10 space-y-8 border-l border-slate-200 pl-8 dark:border-slate-800">
                    @foreach($certifications as $certification)
                        <div class="relative">
                            <span class="absolute -left-[2.15rem] top-1.5 h-3 w-3 rounded-full bg-amber-500"></span>
                            @if($certification->issued_at)
                                <p class="font-mono text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                                    {{ $certification->issued_at->format('M Y') }}
                                </p>
                            @endif
                            <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ $certification->title }}</h3>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $certification->issuer }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Achievements --}}
    @if($achievements->isNotEmpty())
        <section class="bg-white py-20 dark:bg-slate-950">
            <div class="mx-auto max-w-3xl px-6">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Recognition</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Achievements</h2>
                <div class="mt-10 space-y-8 border-l border-slate-200 pl-8 dark:border-slate-800">
                    @foreach($achievements as $achievement)
                        <div class="relative">
                            <span class="absolute -left-[2.15rem] top-1.5 h-3 w-3 rounded-full bg-amber-500"></span>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full border border-slate-200 px-2.5 py-0.5 font-mono text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:text-slate-400">
                                    {{ $categoryLabels[$achievement->category] ?? $achievement->category }}
                                </span>
                                @if($achievement->date)
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                                        {{ $achievement->date->format('M Y') }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
                                @if($achievement->url)
                                    <a href="{{ $achievement->url }}" target="_blank" rel="noopener noreferrer" class="hover:text-amber-600 dark:hover:text-amber-400">{{ $achievement->title }}</a>
                                @else
                                    {{ $achievement->title }}
                                @endif
                            </h3>
                            @if($achievement->organization)
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $achievement->organization }}</p>
                            @endif
                            @if($achievement->description)
                                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $achievement->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Publications --}}
    @if($publications->isNotEmpty())
        <section class="bg-slate-50 py-20 dark:bg-slate-900/40">
            <div class="mx-auto max-w-3xl px-6">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Writing</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Publications</h2>
                <div class="mt-10 space-y-6">
                    @foreach($publications as $publication)
                        <a href="{{ $publication->url }}" target="_blank" rel="noopener noreferrer" class="group block rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-amber-500/40 dark:hover:shadow-black/30">
                            <div class="flex flex-wrap items-center gap-2 font-mono text-xs text-slate-500 dark:text-slate-400">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $publication->publisher }}</span>
                                @if($publication->published_at)
                                    <span>&middot;</span>
                                    <span>{{ $publication->published_at->format('M Y') }}</span>
                                @endif
                                @if($publication->type)
                                    <span>&middot;</span>
                                    <span>{{ $publication->type }}</span>
                                @endif
                            </div>
                            <h3 class="mt-2 flex items-center gap-1.5 text-lg font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">
                                {{ $publication->title }}
                                <svg class="h-4 w-4 text-slate-400 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd" /></svg>
                            </h3>
                            @if($publication->excerpt)
                                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $publication->excerpt }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Gallery --}}
    @if($galleryItems->isNotEmpty())
        <section class="bg-white py-20 dark:bg-slate-950">
            <div class="mx-auto max-w-5xl px-6">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Snapshots</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Gallery</h2>
                <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach($galleryItems as $item)
                        <div class="group relative aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900">
                            <img
                                src="{{ Storage::disk('public')->url($item->image) }}"
                                alt="{{ $item->title ?: ($item->caption ?: Setting::get('name').' — gallery photo '.$loop->iteration) }}"
                                loading="lazy"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                            />
                            @if($item->title || $item->caption)
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/80 to-transparent p-3 opacity-0 transition group-hover:opacity-100">
                                    @if($item->title)
                                        <p class="text-sm font-semibold text-white">{{ $item->title }}</p>
                                    @endif
                                    @if($item->caption)
                                        <p class="text-xs text-slate-300">{{ $item->caption }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
