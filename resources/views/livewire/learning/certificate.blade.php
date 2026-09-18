@php
    use App\Models\Setting;
@endphp

<div class="bg-white dark:bg-slate-900/40">
    <div class="no-print mx-auto flex max-w-3xl items-center justify-end gap-3 px-6 pt-8">
        <button onclick="window.print()" class="rounded-full border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-amber-500 hover:text-amber-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-amber-400 dark:hover:text-amber-400">
            Print
        </button>
        <a href="{{ route('elearning.certificate.download', $course->slug) }}" class="rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600 dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
            Download PDF
        </a>
    </div>

    <section class="mx-auto my-10 max-w-3xl rounded-3xl border-8 border-amber-500 bg-white p-16 text-center shadow-lg">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-amber-600">Certificate of Completion</p>
        <p class="mt-10 text-lg text-slate-500">This certifies that</p>
        <p class="mt-3 text-4xl font-bold text-slate-900">{{ $enrollment->user->name }}</p>
        <p class="mt-6 text-lg text-slate-500">has successfully completed</p>
        <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $course->title }}</p>

        <div class="mx-auto mt-12 flex max-w-sm items-center justify-between border-t border-slate-200 pt-6 text-sm text-slate-500">
            <div>
                <p class="font-semibold text-slate-900">{{ $certificate->issued_at->format('F j, Y') }}</p>
                <p>Date Issued</p>
            </div>
            <div>
                <p class="font-semibold text-slate-900">{{ Setting::get('name') }}</p>
                <p>Instructor</p>
            </div>
        </div>

        <p class="mt-10 text-xs text-slate-400">Certificate No. {{ $certificate->certificate_number }}</p>
    </section>

    <p class="no-print mx-auto max-w-3xl px-6 pb-10 text-center text-xs text-slate-400 dark:text-slate-500">
        Anyone can verify this certificate at
        <a href="{{ route('certificate.verify', ['number' => $certificate->certificate_number]) }}" class="font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">
            {{ route('certificate.verify') }}
        </a>
    </p>

    <style>
        @media print {
            .no-print { display: none; }
            header, footer { display: none; }
        }
    </style>
</div>
