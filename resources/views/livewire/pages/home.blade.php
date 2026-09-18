@php
    use App\Models\Setting;
    use Illuminate\Support\Str;

    $name = Setting::get('name');
    $initials = collect(explode(' ', $name))->map(fn($part) => Str::substr($part, 0, 1))->implode('');
    $topSkills = $skills->take(6);
@endphp

<div>
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-slate-200 bg-white dark:border-slate-900 dark:bg-slate-950">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(245,158,11,0.10),_transparent_55%)]"></div>

        <div class="relative mx-auto max-w-6xl px-6 py-20 sm:py-28">
            <div class="grid items-center gap-16 lg:grid-cols-[1.05fr_0.95fr]">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full border border-slate-200 bg-slate-50 font-mono text-sm font-semibold text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                            {{ $initials }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 font-mono text-xs font-medium text-emerald-700 dark:border-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Available for work
                        </span>
                    </div>

                    <p class="mt-8 font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-600 dark:text-amber-400">
                        {{ Setting::get('tagline') }}
                    </p>
                    <h1 class="mt-4 text-4xl font-bold leading-[1.1] tracking-tight text-slate-900 sm:text-6xl dark:text-white">
                        {{ Setting::get('hero_headline') }}
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ Setting::get('hero_subheadline') }}
                    </p>

                    <div class="mt-10 flex flex-wrap items-center gap-4">
                        <a href="{{ route('projects.index') }}" class="rounded-full bg-slate-900 px-7 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-amber-600 hover:shadow-lg hover:shadow-amber-600/20 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                            View My Work
                        </a>
                        <a href="{{ route('contact') }}" class="rounded-full border border-slate-300 px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-amber-500 hover:text-amber-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-amber-400 dark:hover:text-amber-400">
                            Let's Work Together
                        </a>
                        <a href="{{ route('resume') }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3zM3.5 12a.75.75 0 01.75.75v2.75a1 1 0 001 1h9.5a1 1 0 001-1v-2.75a.75.75 0 011.5 0v2.75a2.5 2.5 0 01-2.5 2.5h-9.5A2.5 2.5 0 012.75 15.5v-2.75A.75.75 0 013.5 12z"/></svg>
                            Download CV
                        </a>
                    </div>

                    <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-slate-100 pt-8 text-sm text-slate-500 dark:border-slate-900 dark:text-slate-400">
                        @if($githubUrl)
                            <a href="{{ $githubUrl }}" target="_blank" rel="noopener noreferrer" class="font-medium transition hover:text-amber-600 dark:hover:text-amber-400">GitHub</a>
                        @endif
                        @if($linkedinUrl)
                            <a href="{{ $linkedinUrl }}" target="_blank" rel="noopener noreferrer" class="font-medium transition hover:text-amber-600 dark:hover:text-amber-400">LinkedIn</a>
                        @endif
                        <a href="mailto:{{ Setting::get('email') }}" class="font-medium transition hover:text-amber-600 dark:hover:text-amber-400">{{ Setting::get('email') }}</a>
                        @if($location)
                            <span class="inline-flex items-center gap-1.5 font-medium">
                                <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg>
                                {{ $location }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Terminal-inspired visual --}}
                <div class="relative">
                    <div class="absolute -inset-4 -z-10 rounded-3xl bg-gradient-to-br from-amber-200/40 to-transparent blur-2xl dark:from-amber-500/10"></div>
                    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-950 shadow-2xl shadow-slate-900/10 dark:shadow-black/40">
                        <div class="flex items-center gap-1.5 border-b border-slate-800 bg-slate-900 px-4 py-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-500/70"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500/70"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500/70"></span>
                            <span class="ml-3 font-mono text-xs text-slate-500">whoami.sh</span>
                        </div>
                        <div class="space-y-2.5 p-6 font-mono text-sm leading-relaxed">
                            <p><span class="text-emerald-400">$</span> <span class="text-slate-300">whoami</span></p>
                            <p class="text-amber-400">{{ Str::slug($name) }}</p>
                            <p class="pt-2"><span class="text-emerald-400">$</span> <span class="text-slate-300">cat role.txt</span></p>
                            <p class="text-slate-400">{{ Setting::get('tagline') }}</p>
                            @if($topSkills->isNotEmpty())
                                <p class="pt-2"><span class="text-emerald-400">$</span> <span class="text-slate-300">stack --top {{ $topSkills->count() }}</span></p>
                                <p class="text-slate-400">[{{ $topSkills->pluck('name')->implode(', ') }}]</p>
                            @endif
                            <p class="pt-2"><span class="text-emerald-400">$</span> <span class="animate-pulse text-slate-300">_</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="border-b border-slate-100 bg-white dark:border-slate-900 dark:bg-slate-950">
        <div class="mx-auto grid max-w-6xl grid-cols-2 gap-8 px-6 py-14 sm:grid-cols-4">
            @foreach($stats as $stat)
                <div class="text-center">
                    <p class="font-mono text-3xl font-bold text-slate-900 sm:text-4xl dark:text-white">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Tech stack / skills --}}
    @if($skills->isNotEmpty())
        <section class="bg-slate-50 py-16 dark:bg-slate-900/40">
            <div class="mx-auto max-w-6xl px-6">
                <h2 class="text-center font-mono text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">Tech Stack</h2>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-x-10 gap-y-6">
                    @foreach($skills as $skill)
                        <span class="text-lg font-semibold text-slate-700 dark:text-slate-300">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- GitHub activity digest --}}
    @if($githubDigest)
        <section class="bg-white py-16 dark:bg-slate-950">
            <div class="mx-auto max-w-3xl px-6">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 dark:border-slate-800 dark:bg-slate-900/60">
                    <div class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">
                        <svg class="h-4 w-4" viewBox="0 0 16 16" fill="currentColor"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/></svg>
                        What I've Been Working On
                    </div>
                    <p class="mt-4 leading-relaxed text-slate-700 dark:text-slate-300">{{ $githubDigest->content }}</p>
                    <p class="mt-4 text-xs text-slate-400 dark:text-slate-500">Auto-generated from recent GitHub activity &middot; {{ $githubDigest->generated_at->diffForHumans() }}</p>
                </div>
            </div>
        </section>
    @endif

    {{-- Services --}}
    @if($services->isNotEmpty())
        <section class="bg-white py-20 dark:bg-slate-950">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex items-end justify-between">
                    <div>
                        <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Services</p>
                        <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">What I Do</h2>
                    </div>
                    <a href="{{ route('services.index') }}" class="hidden text-sm font-semibold text-amber-600 hover:text-amber-700 sm:inline dark:text-amber-400 dark:hover:text-amber-300">
                        View all services →
                    </a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($services as $service)
                        <a href="{{ route('services.show', $service->slug) }}" class="group rounded-2xl border border-slate-200 p-6 transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:hover:border-amber-500/40 dark:hover:shadow-black/30">
                            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $service->title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $service->summary }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured projects --}}
    @if($featuredProjects->isNotEmpty())
        <section class="bg-slate-50 py-20 dark:bg-slate-900/40">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex items-end justify-between">
                    <div>
                        <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Work</p>
                        <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Featured Work</h2>
                    </div>
                    <a href="{{ route('projects.index') }}" class="hidden text-sm font-semibold text-amber-600 hover:text-amber-700 sm:inline dark:text-amber-400 dark:hover:text-amber-300">
                        View all projects →
                    </a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2">
                    @foreach($featuredProjects as $project)
                        <a href="{{ route('projects.show', $project->slug) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900 dark:hover:shadow-black/30">
                            @if($project->cover_image)
                                <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" class="h-48 w-full object-cover" />
                            @else
                                <div class="flex h-48 w-full items-center justify-center bg-slate-900 text-white dark:bg-slate-950">
                                    <span class="font-mono text-sm font-semibold uppercase tracking-widest">{{ $project->client }}</span>
                                </div>
                            @endif
                            <div class="p-6">
                                <p class="font-mono text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">{{ $project->client }}</p>
                                <h3 class="mt-1 text-lg font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">{{ $project->title }}</h3>
                                <p class="mt-2 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">{{ $project->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
        <section class="bg-white py-20 dark:bg-slate-950">
            <div class="mx-auto max-w-4xl px-6 text-center">
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Testimonials</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">What Clients Say</h2>
                <div class="mt-10 grid gap-8 sm:grid-cols-2">
                    @foreach($testimonials as $testimonial)
                        <blockquote class="rounded-2xl border border-slate-200 p-6 text-left dark:border-slate-800">
                            <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-300">&ldquo;{{ $testimonial->content }}&rdquo;</p>
                            <footer class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">
                                {{ $testimonial->client_name }}
                                @if($testimonial->client_company)
                                    <span class="font-normal text-slate-500 dark:text-slate-400">— {{ $testimonial->client_company }}</span>
                                @endif
                            </footer>
                        </blockquote>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Trusted companies --}}
    @if($trustedCompanies->isNotEmpty())
        <section class="border-t border-slate-100 bg-slate-50 py-14 dark:border-slate-900 dark:bg-slate-900/40">
            <div class="mx-auto max-w-6xl px-6">
                <p class="text-center font-mono text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Trusted By</p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-10">
                    @foreach($trustedCompanies as $company)
                        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="h-8 opacity-70 grayscale transition hover:opacity-100 hover:grayscale-0 dark:opacity-60 dark:invert" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-2xl px-6">
            <h2 class="text-3xl font-bold text-white">Have a project in mind?</h2>
            <p class="mt-3 text-slate-300">Let's talk about how I can help bring it to life.</p>
            <a href="{{ route('contact') }}" class="mt-8 inline-block rounded-full bg-amber-500 px-7 py-3 text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:bg-amber-400 hover:shadow-lg hover:shadow-amber-500/20">
                Start a Conversation
            </a>
        </div>
    </section>
</div>
