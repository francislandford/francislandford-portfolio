<div>
    <section class="flex min-h-[70vh] items-center justify-center bg-slate-50 px-6 py-16 dark:bg-slate-900/40">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <a href="{{ route('elearning.show', $course->slug) }}" class="font-mono text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">&larr; {{ $course->title }}</a>
            <h1 class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">Checkout</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ number_format($course->price, 2) }} {{ $course->currency }} &mdash; pay with mobile money.
            </p>

            @if($payment)
                <div class="mt-6 rounded-xl border border-slate-200 p-5 text-center dark:border-slate-800" wire:poll.3s="refreshStatus">
                    @if($payment->status === 'pending')
                        <p class="font-semibold text-slate-900 dark:text-white">Check your phone</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            Approve the {{ $payment->gateway === 'mtn_momo' ? 'MTN MoMo' : 'Orange Money' }} payment prompt sent to {{ $payment->phone_number }}.
                        </p>
                        @if(!empty($payment->meta['payment_url']))
                            <a href="{{ $payment->meta['payment_url'] }}" target="_blank" class="mt-4 inline-block text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">
                                Open payment page →
                            </a>
                        @endif
                        <div class="mt-4 flex items-center justify-center gap-2 font-mono text-xs text-slate-400 dark:text-slate-500">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Waiting for confirmation…
                        </div>
                    @elseif($payment->status === 'successful')
                        <p class="font-semibold text-emerald-700 dark:text-emerald-400">Payment successful!</p>
                        <a href="{{ route('elearning.dashboard') }}" class="mt-4 inline-block rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                            Go to My Courses
                        </a>
                    @else
                        <p class="font-semibold text-red-700 dark:text-red-400">Payment failed.</p>
                        <button wire:click="retry" class="mt-4 rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                            Try Again
                        </button>
                    @endif
                </div>
            @else
                <form wire:submit="pay" class="mt-6 space-y-5">
                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Payment Method</label>
                        <div class="mt-2 grid grid-cols-2 gap-3">
                            <label class="flex cursor-pointer items-center justify-center rounded-lg border px-4 py-3 text-sm font-medium {{ $gateway === 'mtn_momo' ? 'border-amber-500 bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : 'border-slate-200 text-slate-600 dark:border-slate-800 dark:text-slate-400' }}">
                                <input type="radio" wire:model.live="gateway" value="mtn_momo" class="sr-only" />
                                MTN MoMo
                            </label>
                            <label class="flex cursor-pointer items-center justify-center rounded-lg border px-4 py-3 text-sm font-medium {{ $gateway === 'orange_money' ? 'border-amber-500 bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : 'border-slate-200 text-slate-600 dark:border-slate-800 dark:text-slate-400' }}">
                                <input type="radio" wire:model.live="gateway" value="orange_money" class="sr-only" />
                                Orange Money
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Phone Number</label>
                        <input type="text" wire:model="phone_number" placeholder="e.g. 0777123456" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" />
                        @error('phone_number') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    @if($error)
                        <p class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-400">{{ $error }}</p>
                    @endif

                    <button type="submit" wire:loading.attr="disabled" class="w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 disabled:opacity-60 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                        <span wire:loading.remove>Pay {{ number_format($course->price, 2) }} {{ $course->currency }}</span>
                        <span wire:loading>Processing…</span>
                    </button>
                </form>
            @endif
        </div>
    </section>
</div>
