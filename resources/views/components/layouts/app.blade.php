@php
    use App\Models\Setting;
    use App\Models\Service;
    use App\Models\SocialLink;

    $siteName = Setting::get('name', 'Francis Landford');
    $siteTagline = Setting::get('tagline');
    $siteDescription = Setting::get('site_meta_description');
    $navServices = Service::query()->where('is_active', true)->orderBy('order')->get();
    $socialLinks = SocialLink::query()->where('is_active', true)->orderBy('order')->get();

    $pageTitle = $title ?? $siteName;
    $pageDescription = $description ?? $siteDescription;

    $personSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $siteName,
        'jobTitle' => $siteTagline,
        'description' => $siteDescription,
        'url' => Setting::get('website', url('/')),
        'email' => Setting::get('email'),
        'address' => Setting::get('address'),
        'sameAs' => $socialLinks->pluck('url')->values()->all(),
    ]);

    $webSiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $siteName,
        'url' => url('/'),
    ];

    $structuredData = array_merge([$personSchema, $webSiteSchema], $structuredData ?? []);
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $pageTitle }}{{ isset($title) ? ' — '.$siteName : ' — '.$siteTagline }}</title>
    <meta name="description" content="{{ $pageDescription }}" />

    <meta property="og:type" content="{{ $ogType ?? 'website' }}" />
    <meta property="og:site_name" content="{{ $siteName }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $pageTitle }}" />
    <meta property="og:description" content="{{ $pageDescription }}" />
    @if(!empty($ogImage))
        <meta property="og:image" content="{{ $ogImage }}" />
    @endif

    <meta name="twitter:card" content="{{ !empty($ogImage) ? 'summary_large_image' : 'summary' }}" />
    <meta name="twitter:title" content="{{ $pageTitle }}" />
    <meta name="twitter:description" content="{{ $pageDescription }}" />
    @if(!empty($ogImage))
        <meta name="twitter:image" content="{{ $ogImage }}" />
    @endif

    <link rel="canonical" href="{{ url()->current() }}" />

    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="icon" type="image/png" href="/favicon.png" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    @foreach($structuredData as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endforeach

    <script>
        (function () {
            var stored = localStorage.getItem('theme');
            var isDark = stored === 'dark' || (stored !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">

    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-950/90">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="flex items-baseline gap-0.5 text-lg font-bold tracking-tight text-slate-900 dark:text-white">
                {{ $siteName }}<span class="font-mono text-amber-500">.</span>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-medium text-slate-600 md:flex dark:text-slate-400">
                <a href="{{ route('home') }}" class="transition hover:text-amber-600 dark:hover:text-amber-400 {{ request()->routeIs('home') ? 'text-amber-600 dark:text-amber-400' : '' }}">Home</a>
                <a href="{{ route('about') }}" class="transition hover:text-amber-600 dark:hover:text-amber-400 {{ request()->routeIs('about') ? 'text-amber-600 dark:text-amber-400' : '' }}">About</a>

                <div class="group relative">
                    <button class="flex items-center gap-1 transition hover:text-amber-600 dark:hover:text-amber-400 {{ request()->routeIs('services.*') ? 'text-amber-600 dark:text-amber-400' : '' }}">
                        Services
                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                    </button>
                    <div class="invisible absolute left-1/2 top-full w-72 -translate-x-1/2 pt-3 opacity-0 transition group-hover:visible group-hover:opacity-100">
                        <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900 dark:shadow-black/30">
                            @foreach($navServices as $navService)
                                <a href="{{ route('services.show', $navService->slug) }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-500/10 dark:hover:text-amber-400">
                                    {{ $navService->title }}
                                </a>
                            @endforeach
                            <a href="{{ route('services.index') }}" class="mt-1 block rounded-lg px-3 py-2 text-sm font-semibold text-amber-600 hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-500/10">
                                View all services →
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('projects.index') }}" class="transition hover:text-amber-600 dark:hover:text-amber-400 {{ request()->routeIs('projects.*') ? 'text-amber-600 dark:text-amber-400' : '' }}">Projects</a>
                <a href="{{ route('blog.index') }}" class="transition hover:text-amber-600 dark:hover:text-amber-400 {{ request()->routeIs('blog.*') ? 'text-amber-600 dark:text-amber-400' : '' }}">Blog</a>
                <a href="{{ route('elearning') }}" class="transition hover:text-amber-600 dark:hover:text-amber-400 {{ request()->routeIs('elearning*') ? 'text-amber-600 dark:text-amber-400' : '' }}">E-Learning</a>

                @auth
                    <a href="{{ route('elearning.dashboard') }}" class="transition hover:text-amber-600 dark:hover:text-amber-400 {{ request()->routeIs('elearning.dashboard') ? 'text-amber-600 dark:text-amber-400' : '' }}">My Courses</a>
                @else
                    <a href="{{ route('login') }}" class="transition hover:text-amber-600 dark:hover:text-amber-400 {{ request()->routeIs('login') ? 'text-amber-600 dark:text-amber-400' : '' }}">Sign In</a>
                @endauth
            </nav>

            <div class="hidden items-center gap-1 md:flex">
                <a href="{{ route('search') }}" class="rounded-full p-2 text-slate-500 transition hover:bg-slate-100 hover:text-amber-600 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-amber-400" aria-label="Search">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                </a>

                <x-theme-toggle />

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 text-sm font-medium text-slate-600 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-400">Sign Out</button>
                    </form>
                @endauth
                <a href="{{ route('contact') }}" class="ml-2 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                    Contact Us
                </a>
            </div>

            <div class="flex items-center gap-1 md:hidden">
                <x-theme-toggle />

                <div x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 text-slate-700 dark:text-slate-300" aria-label="Toggle menu">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false" class="absolute inset-x-0 top-full border-b border-slate-200 bg-white px-6 py-4 shadow-lg dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex flex-col gap-3 text-sm font-medium text-slate-700 dark:text-slate-300">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('about') }}">About</a>
                            <a href="{{ route('services.index') }}">Services</a>
                            <a href="{{ route('projects.index') }}">Projects</a>
                            <a href="{{ route('blog.index') }}">Blog</a>
                            <a href="{{ route('elearning') }}">E-Learning</a>
                            <a href="{{ route('search') }}">Search</a>
                            @auth
                                <a href="{{ route('elearning.dashboard') }}">My Courses</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-left">Sign Out</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}">Sign In</a>
                            @endauth
                            <a href="{{ route('contact') }}" class="font-semibold text-amber-600 dark:text-amber-400">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="border-t border-slate-900 bg-slate-950 text-slate-300">
        <div class="mx-auto max-w-6xl px-6 py-14">
            <div class="grid gap-10 md:grid-cols-3">
                <div>
                    <p class="flex items-baseline gap-0.5 text-lg font-bold text-white">{{ $siteName }}<span class="font-mono text-amber-500">.</span></p>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400">{{ $siteTagline }}</p>
                </div>

                <div class="text-sm text-slate-400">
                    <p class="font-mono text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Contact</p>
                    <p class="mt-3">{{ Setting::get('email') }}</p>
                    <p class="mt-1">{{ Setting::get('phone') }}</p>
                    <p class="mt-1">{{ Setting::get('address') }}</p>
                </div>

                <div class="text-sm text-slate-400">
                    <p class="font-mono text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Follow</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        @foreach($socialLinks as $link)
                            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-slate-800 px-3 py-1.5 transition hover:border-amber-500 hover:text-amber-400">
                                {{ $link->platform }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-col gap-2 border-t border-slate-800 pt-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <p class="font-mono">Built with Laravel &amp; Livewire</p>
            </div>
        </div>
    </footer>

    <livewire:chatbot.widget />

    @livewireScripts
</body>
</html>
