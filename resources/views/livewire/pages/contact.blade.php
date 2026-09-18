@php
    use App\Models\Setting;
    use App\Models\SocialLink;

    $socialLinks = SocialLink::query()->where('is_active', true)->orderBy('order')->get();
@endphp

<div>
    <section class="bg-slate-950 py-20 text-center">
        <div class="mx-auto max-w-3xl px-6">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">Contact</p>
            <h1 class="mt-4 text-4xl font-bold text-white sm:text-5xl">Let's Work Together</h1>
            <p class="mt-4 text-lg text-slate-300">Have a project in mind? Send a message and I'll get back to you.</p>
        </div>
    </section>

    <section class="bg-white py-20 dark:bg-slate-950">
        <div class="mx-auto grid max-w-5xl gap-12 px-6 sm:grid-cols-5">
            <div class="sm:col-span-2">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 font-mono text-xs font-medium text-emerald-700 dark:border-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Available for work
                </span>

                <h2 class="mt-6 text-lg font-semibold text-slate-900 dark:text-white">Get in Touch</h2>
                <div class="mt-6 space-y-5 text-sm text-slate-600 dark:text-slate-400">
                    <div>
                        <p class="font-mono text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Email</p>
                        <a href="mailto:{{ Setting::get('email') }}" class="mt-1 block font-medium text-slate-900 hover:text-amber-600 dark:text-white dark:hover:text-amber-400">{{ Setting::get('email') }}</a>
                    </div>
                    <div>
                        <p class="font-mono text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Phone</p>
                        <a href="tel:{{ Setting::get('phone') }}" class="mt-1 block font-medium text-slate-900 hover:text-amber-600 dark:text-white dark:hover:text-amber-400">{{ Setting::get('phone') }}</a>
                    </div>
                    <div>
                        <p class="font-mono text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Address</p>
                        <p class="mt-1 font-medium text-slate-900 dark:text-white">{{ Setting::get('address') }}</p>
                    </div>
                </div>

                @if($socialLinks->isNotEmpty())
                    <div class="mt-8 border-t border-slate-100 pt-6 dark:border-slate-900">
                        <p class="font-mono text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Follow</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($socialLinks as $link)
                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-slate-200 px-3 py-1.5 text-sm text-slate-600 transition hover:border-amber-300 hover:text-amber-600 dark:border-slate-800 dark:text-slate-400 dark:hover:border-amber-500/40 dark:hover:text-amber-400">
                                    {{ $link->platform }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="sm:col-span-3">
                @if($sent)
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-center dark:border-emerald-900 dark:bg-emerald-500/10">
                        <p class="font-semibold text-emerald-800 dark:text-emerald-400">Message sent — thank you!</p>
                        <p class="mt-1 text-sm text-emerald-700 dark:text-emerald-300">I'll get back to you as soon as possible.</p>
                    </div>
                @else
                    <form wire:submit="send" class="space-y-5">
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                            <input type="text" wire:model="name" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" />
                            @error('name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                            <input type="email" wire:model="email" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" />
                            @error('email') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Phone (optional)</label>
                            <input type="text" wire:model="phone" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" />
                            @error('phone') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Subject (optional)</label>
                            <input type="text" wire:model="subject" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" />
                            @error('subject') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Message</label>
                            <textarea wire:model="message" rows="5" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"></textarea>
                            @error('message') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 disabled:opacity-60 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                            <span wire:loading.remove>Send Message</span>
                            <span wire:loading>Sending…</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>
