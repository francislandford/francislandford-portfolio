<div>
    <section class="flex min-h-[70vh] items-center justify-center bg-slate-50 px-6 py-16 dark:bg-slate-900/40">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-amber-600 dark:text-amber-400">Get started</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">Create your account</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Sign up to enroll in courses and track your progress.</p>

            <form wire:submit="register" class="mt-6 space-y-5">
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
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                    <input type="password" wire:model="password" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" />
                    @error('password') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Confirm Password</label>
                    <input type="password" wire:model="password_confirmation" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" />
                </div>

                <button type="submit" wire:loading.attr="disabled" class="w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 disabled:opacity-60 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                    Create Account
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">Sign in</a>
            </p>
        </div>
    </section>
</div>
