<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $name }} — Resume</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="mx-auto max-w-3xl bg-white px-10 py-12 text-slate-900">

    <div class="no-print mb-8 flex justify-end">
        <button onclick="window.print()" class="rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
            Print / Save as PDF
        </button>
    </div>

    <header class="border-b-2 border-slate-900 pb-6">
        <h1 class="text-3xl font-bold tracking-tight">{{ $name }}</h1>
        <p class="mt-1 font-mono text-sm font-medium text-amber-600">{{ $tagline }}</p>
        <p class="mt-3 text-xs text-slate-500">
            {{ $email }} &middot; {{ $phone }} &middot; {{ $address }}
        </p>
    </header>

    <section class="mt-8">
        <h2 class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Summary</h2>
        <p class="mt-3 text-sm leading-relaxed text-slate-700">{{ $heroSubheadline }}</p>
    </section>

    <section class="mt-8">
        <h2 class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Experience</h2>
        <div class="mt-3 space-y-5">
            @forelse($experiences as $experience)
                <div>
                    <div class="flex items-baseline justify-between">
                        <h3 class="text-sm font-semibold">{{ $experience->role_title }} &middot; {{ $experience->company }}</h3>
                        <span class="text-xs text-slate-500">
                            {{ $experience->start_date->format('M Y') }} —
                            {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                        </span>
                    </div>
                    @if($experience->description)
                        <ul class="mt-1 list-disc space-y-0.5 pl-4 text-sm leading-relaxed text-slate-600">
                            @foreach(explode("\n", $experience->description) as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-500">Experience details coming soon.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-8">
        <h2 class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Education</h2>
        <div class="mt-3 space-y-3">
            @forelse($educations as $education)
                <div class="flex items-baseline justify-between">
                    <h3 class="text-sm font-semibold">{{ $education->degree }} &middot; {{ $education->institution }}</h3>
                    <span class="text-xs text-slate-500">
                        @if($education->start_date && $education->end_date && $education->start_date->format('Y') !== $education->end_date->format('Y'))
                            {{ $education->start_date->format('Y') }} — {{ $education->end_date->format('Y') }}
                        @elseif($education->end_date)
                            {{ $education->end_date->format('Y') }}
                        @endif
                    </span>
                </div>
            @empty
                <p class="text-sm text-slate-500">Education details coming soon.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-8">
        <h2 class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Skills</h2>
        <p class="mt-3 text-sm text-slate-700">
            @forelse($skills as $skill){{ $skill->name }}{{ !$loop->last ? ', ' : '' }}@empty Coming soon.@endforelse
        </p>
    </section>

    <section class="mt-8">
        <h2 class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Certifications</h2>
        <div class="mt-3 space-y-2">
            @forelse($certifications as $certification)
                <div class="flex items-baseline justify-between">
                    <h3 class="text-sm font-semibold">{{ $certification->title }} &middot; {{ $certification->issuer }}</h3>
                    @if($certification->issued_at)
                        <span class="text-xs text-slate-500">{{ $certification->issued_at->format('M Y') }}</span>
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-500">Certifications coming soon.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-8">
        <h2 class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Selected Projects</h2>
        <div class="mt-3 space-y-3">
            @forelse($projects as $project)
                <div>
                    <h3 class="text-sm font-semibold">{{ $project->title }} @if($project->client)<span class="font-normal text-slate-500">— {{ $project->client }}</span>@endif</h3>
                    <p class="text-sm leading-relaxed text-slate-600">{{ $project->description }}</p>
                </div>
            @empty
                <p class="text-sm text-slate-500">Projects coming soon.</p>
            @endforelse
        </div>
    </section>

</body>
</html>
