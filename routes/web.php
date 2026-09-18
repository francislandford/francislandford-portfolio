<?php

use App\Livewire\Auth;
use App\Livewire\Blog;
use App\Livewire\Learning;
use App\Livewire\Pages;
use App\Livewire\Projects;
use App\Livewire\Search;
use App\Livewire\Services;
use App\Models\Certificate as CertificateModel;
use App\Models\Certification;
use App\Models\Course;
use App\Models\Education;
use App\Models\Enrollment;
use App\Models\Experience;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Services\Payments\CoursePaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Support\Facades\Route;

Route::middleware('track.visits')->group(function () {
    Route::get('/', Pages\Home::class)->name('home');
    Route::get('/about', Pages\About::class)->name('about');
    Route::get('/contact', Pages\Contact::class)->name('contact');

    Route::get('/services', Services\Index::class)->name('services.index');
    Route::get('/services/{service}', Services\Show::class)->name('services.show');

    Route::get('/projects', Projects\Index::class)->name('projects.index');
    Route::get('/projects/{project}', Projects\Show::class)->name('projects.show');

    Route::get('/blog', Blog\Index::class)->name('blog.index');
    Route::get('/blog/{post}', Blog\Show::class)->name('blog.show');

    Route::get('/search', Search\Index::class)->name('search');

    Route::get('/resume', function () {
        return view('resume', [
            'name' => Setting::get('name'),
            'tagline' => Setting::get('tagline'),
            'email' => Setting::get('email'),
            'phone' => Setting::get('phone'),
            'address' => Setting::get('address'),
            'heroSubheadline' => Setting::get('hero_subheadline'),
            'experiences' => Experience::query()->orderByDesc('start_date')->get(),
            'skills' => Skill::query()->where('is_active', true)->orderBy('order')->get(),
            'educations' => Education::query()->orderByDesc('start_date')->get(),
            'certifications' => Certification::query()->orderByDesc('issued_at')->get(),
            'projects' => Project::query()->where('status', 'published')->orderByDesc('is_featured')->orderBy('order')->limit(4)->get(),
        ]);
    })->name('resume');
});

Route::get('/sitemap.xml', function () {
    $urls = collect([
        ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.7'],
        ['loc' => route('services.index'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => route('projects.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
        ['loc' => route('blog.index'), 'changefreq' => 'daily', 'priority' => '0.8'],
        ['loc' => route('elearning'), 'changefreq' => 'monthly', 'priority' => '0.5'],
        ['loc' => route('contact'), 'changefreq' => 'yearly', 'priority' => '0.5'],
    ]);

    $urls = $urls
        ->concat(Service::query()->where('is_active', true)->get()->map(fn (Service $service) => [
            'loc' => route('services.show', $service->slug),
            'lastmod' => $service->updated_at->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.7',
        ]))
        ->concat(Project::query()->where('status', 'published')->get()->map(fn (Project $project) => [
            'loc' => route('projects.show', $project->slug),
            'lastmod' => $project->updated_at->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.7',
        ]))
        ->concat(Post::query()->where('status', 'published')->get()->map(fn (Post $post) => [
            'loc' => route('blog.show', $post->slug),
            'lastmod' => $post->updated_at->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.6',
        ]));

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/register', Auth\Register::class)->name('register');
    Route::get('/login', Auth\Login::class)->name('login');
});

Route::post('/logout', function (Request $request) {
    AuthFacade::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

// E-Learning
Route::middleware('track.visits')->group(function () {
    Route::get('/e-learning', Learning\Index::class)->name('elearning');
    Route::get('/e-learning/{course}', Learning\Show::class)->name('elearning.show');
    Route::get('/e-learning/{course}/lessons/{lesson}', Learning\Lesson::class)->name('elearning.lesson');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Learning\Dashboard::class)->name('elearning.dashboard');
    Route::get('/e-learning/{course}/checkout', Learning\Checkout::class)->name('elearning.checkout');
    Route::get('/e-learning/{course}/quiz', Learning\Quiz::class)->name('elearning.quiz');
    Route::get('/e-learning/{course}/certificate', Learning\Certificate::class)->name('elearning.certificate');

    Route::get('/e-learning/{course}/certificate/download', function (Course $course) {
        $enrollment = Enrollment::where('user_id', AuthFacade::id())->where('course_id', $course->id)->firstOrFail();

        abort_unless($enrollment->isEligibleForCertificate(), 403);

        $certificate = CertificateModel::firstOrCreate(
            ['enrollment_id' => $enrollment->id],
            ['issued_at' => now()]
        );

        $pdf = Pdf::loadView('pdf.certificate', [
            'siteName' => Setting::get('name'),
            'studentName' => AuthFacade::user()->name,
            'courseTitle' => $course->title,
            'issuedAt' => $certificate->issued_at->format('F j, Y'),
            'certificateNumber' => $certificate->certificate_number,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("certificate-{$certificate->certificate_number}.pdf");
    })->name('elearning.certificate.download');
});

// Public certificate verification
Route::get('/certificate/verify', function (Request $request) {
    $number = $request->query('number');

    $certificate = $number
        ? CertificateModel::where('certificate_number', $number)->with('enrollment.user', 'enrollment.course')->first()
        : null;

    return view('certificate-verify', [
        'number' => $number,
        'certificate' => $certificate,
    ]);
})->name('certificate.verify');

// Payment gateway callbacks
Route::get('/e-learning/payment/{payment}/return', function (Payment $payment) {
    return redirect()->route('elearning.checkout', $payment->course->slug);
})->name('elearning.payment.return');

Route::get('/e-learning/payment/{payment}/cancel', function (Payment $payment) {
    $payment->update(['status' => 'failed']);

    return redirect()->route('elearning.show', $payment->course->slug);
})->name('elearning.payment.cancel');

Route::post('/e-learning/payment/webhook/{gateway}', function (Request $request, string $gateway, CoursePaymentService $paymentService) {
    $orderId = $request->input('order_id') ?? $request->input('externalId');
    $payment = Payment::where('id', $orderId)->where('gateway', $gateway)->first();

    if ($payment) {
        $paymentService->refreshStatus($payment);
    }

    return response()->noContent();
})->middleware('throttle:30,1')->name('elearning.payment.webhook');
