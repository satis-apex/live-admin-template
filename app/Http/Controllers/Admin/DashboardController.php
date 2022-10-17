<?php
namespace App\Http\Controllers\Admin;

use Inertia\Inertia;

class DashboardController
{
    public function __invoke()
    {
        return Inertia::render('Admin/Home', [
            'breadcrumb' => getBreadcrumb()
        ]);
    }
}
