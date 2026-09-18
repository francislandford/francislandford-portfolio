<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Portfolio/content models an Editor manages.
     */
    private const EDITOR_MODELS = [
        'Project', 'Service', 'Post', 'Category', 'Tag',
        'Testimonial', 'TrustedCompany', 'GalleryItem',
        'Experience', 'Education', 'Skill', 'Certification',
        'Achievement', 'Publication', 'SocialLink',
    ];

    /**
     * E-Learning models an Instructor manages.
     */
    private const INSTRUCTOR_MODELS = [
        'Course', 'Lesson', 'Quiz', 'QuizQuestion',
        'Enrollment', 'Certificate', 'Payment', 'BudgetCap',
    ];

    /**
     * Content/traffic dashboard widgets relevant to an Editor.
     */
    private const EDITOR_WIDGETS = [
        'ContentOverview', 'VisitorOverview', 'PageViewsChart', 'TopPages',
    ];

    /**
     * E-Learning dashboard widgets relevant to an Instructor.
     */
    private const INSTRUCTOR_WIDGETS = [
        'EnrollmentsChart',
    ];

    public function run(): void
    {
        $allPermissions = Permission::pluck('name');

        // Super Admin: every permission, unrestricted.
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($allPermissions);

        // Admin: everything except managing roles themselves.
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(
            $allPermissions->reject(fn ($name) => str_ends_with($name, ':Role'))
        );

        // Editor: portfolio and profile content, plus content/traffic dashboard widgets.
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions(
            $allPermissions->filter(fn ($name) => in_array(
                explode(':', $name)[1] ?? '',
                [...self::EDITOR_MODELS, ...self::EDITOR_WIDGETS]
            ))
        );

        // Instructor: E-Learning content, enrollment/payment visibility, and its dashboard widget.
        $instructor = Role::firstOrCreate(['name' => 'instructor', 'guard_name' => 'web']);
        $instructor->syncPermissions(
            $allPermissions->filter(fn ($name) => in_array(
                explode(':', $name)[1] ?? '',
                [...self::INSTRUCTOR_MODELS, ...self::INSTRUCTOR_WIDGETS]
            ))
        );
    }
}
