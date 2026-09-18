<div>
    <section class="flex min-h-[70vh] items-center justify-center bg-slate-50 px-6 py-16">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Sign in</h1>
            <p class="mt-1 text-sm text-slate-500">Welcome back. Sign in to continue learning.</p>

            <form wire:submit="login" class="mt-6 space-y-5">
                <div>
                    <label class="text-sm font-medium text-slate-700">Email</label>
                    <input type="email" wire:model="email" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500" />
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Password</label>
                    <input type="password" wire:model="password" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500" />
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" wire:model="remember" class="rounded border-slate-300" />
                    Remember me
                </label>

                <button type="submit" wire:loading.attr="disabled" class="w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-amber-600 disabled:opacity-60">
                    Sign In
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-semibold text-amber-600 hover:text-amber-700">Create one</a>
            </p>
        </div>
    </section>
</div>
