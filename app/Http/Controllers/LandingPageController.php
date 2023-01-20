<?php
namespace App\Http\Controllers;

use Inertia\Inertia;

class LandingPageController
{
    public function __construct()
    {
        Inertia::setRootView('app');
    }

    public function index()
    {
        return Inertia::render('Landing', [
            'breadcrumb' => getBreadcrumb()
        ]);
    }
}
