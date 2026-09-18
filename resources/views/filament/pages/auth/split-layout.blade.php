@props([
    'after' => null,
    'heading' => null,
    'subheading' => null,
])

@php
    use Filament\Livewire\SimpleUserMenu;
    use Filament\Support\Enums\Width;
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;
    use App\Models\Setting;
    use App\Models\Skill;
    use Illuminate\Support\Str;

    $livewire ??= null;

    $renderHookScopes = $livewire?->getRenderHookScopes();
    $maxContentWidth ??= (filament()->getSimplePageMaxContentWidth() ?? Width::Large);

    if (is_string($maxContentWidth)) {
        $maxContentWidth = Width::tryFrom($maxContentWidth) ?? $maxContentWidth;
    }

    $siteName = Setting::get('name', config('app.name'));
    $siteTagline = Setting::get('tagline');
    $topSkills = Skill::query()->where('is_active', true)->orderBy('order')->limit(6)->pluck('name');
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="fi-simple-layout min-h-screen lg:grid lg:grid-cols-2">
        @if (($hasTopbar ?? true) && filament()->auth()->check())
            <a href="#fi-main-content" class="fi-skip-link fi-sr-only">
                {{ __('filament-panels::layout.skip_to_content.label') }}
            </a>
        @endif

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        {{-- Brand panel --}}
        <div class="relative hidden overflow-hidden bg-slate-950 lg:flex lg:flex-col lg:justify-between lg:p-12">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(245,158,11,0.12),_transparent_55%)]"></div>

            <div class="relative">
                <a href="{{ url('/') }}" class="flex items-baseline gap-0.5 text-lg font-bold tracking-tight text-white">
                    {{ $siteName }}<span class="font-mono text-amber-500">.</span>
                </a>

                <p class="mt-16 font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">Admin Console</p>
                <h1 class="mt-4 max-w-md text-3xl font-bold leading-tight text-white">
                    Manage every part of the platform from one place.
                </h1>
                @if($siteTagline)
                    <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-400">{{ $siteTagline }}</p>
                @endif
            </div>

            <div class="relative">
                <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/60 shadow-2xl shadow-black/40 backdrop-blur">
                    <div class="flex items-center gap-1.5 border-b border-slate-800 bg-slate-900 px-4 py-3">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-500/70"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-500/70"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500/70"></span>
                        <span class="ml-3 font-mono text-xs text-slate-500">session.sh</span>
                    </div>
                    <div class="space-y-2.5 p-6 font-mono text-sm leading-relaxed">
                        <p><span class="text-emerald-400">$</span> <span class="text-slate-300">whoami</span></p>
                        <p class="text-amber-400">{{ 'admin@'.Str::slug($siteName) }}</p>
                        <p class="pt-2"><span class="text-emerald-400">$</span> <span class="text-slate-300">status</span></p>
                        <p class="text-slate-400">awaiting authentication&hellip;</p>
                        @if($topSkills->isNotEmpty())
                            <p class="pt-2"><span class="text-emerald-400">$</span> <span class="text-slate-300">stack --top {{ $topSkills->count() }}</span></p>
                            <p class="text-slate-400">[{{ $topSkills->implode(', ') }}]</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Auth form --}}
        <div class="fi-simple-main-ctn flex items-center justify-center bg-slate-50 px-6 py-12 dark:bg-slate-950 lg:px-12">
            <main
                id="fi-main-content"
                tabindex="-1"
                @class([
                    'fi-simple-main w-full',
                    ($maxContentWidth instanceof Width) ? "fi-width-{$maxContentWidth->value}" : $maxContentWidth,
                ])
            >
                {{ $slot }}
            </main>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::FOOTER, scopes: $renderHookScopes) }}

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>
</x-filament-panels::layout.base>
