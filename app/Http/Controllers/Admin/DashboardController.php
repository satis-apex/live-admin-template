<?php
namespace App\Http\Controllers\Admin;

use Inertia\Inertia;

class DashboardController
{
    public function index()
    {
        return Inertia::render('Admin/Home');
    }
}
