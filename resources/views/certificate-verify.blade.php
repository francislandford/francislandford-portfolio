@php
    use App\Models\Setting;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Verify Certificate — {{ Setting::get('name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <script>
        (function () {
            var stored = localStorage.getItem('theme');
            var isDark = stored === 'dark' || (stored !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
    @vite(['resources/css/app.css'])
    {{ Vite::fonts() }}
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div class="mx-auto max-w-xl px-6 py-16">
        <a href="{{ route('home') }}" class="font-mono text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">&larr; {{ Setting::get('name') }}</a>

        <h1 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">Verify a Certificate</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Enter a certificate number to confirm it was issued by {{ Setting::get('name') }}.</p>

        <form method="GET" action="{{ route('certificate.verify') }}" class="mt-6 flex gap-3">
            <input
                type="text"
                name="number"
                value="{{ $number }}"
                placeholder="e.g. CERT-2026-ABCD1234"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            />
            <button type="submit" class="whitespace-nowrap rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                Verify
            </button>
        </form>

        @if($number)
            <div class="mt-8">
                @if($certificate)
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-900 dark:bg-emerald-500/10">
                        <p class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Valid Certificate
                        </p>
                        <p class="mt-4 text-xl font-bold text-slate-900 dark:text-white">{{ $certificate->enrollment->user->name }}</p>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">completed <span class="font-semibold">{{ $certificate->enrollment->course->title }}</span></p>
                        <p class="mt-4 font-mono text-xs text-slate-500 dark:text-slate-400">
                            Issued {{ $certificate->issued_at->format('F j, Y') }} &middot; Certificate No. {{ $certificate->certificate_number }}
                        </p>
                    </div>
                @else
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-6 text-sm font-medium text-red-700 dark:border-red-900 dark:bg-red-500/10 dark:text-red-400">
                        No certificate found for "{{ $number }}". Double-check the certificate number and try again.
                    </div>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
