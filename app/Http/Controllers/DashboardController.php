<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $hasZohoAccount = auth()->user()->zohoAccount !== null;

        return Inertia::render('dashboard', [
            'hasZohoAccount' => $hasZohoAccount,
        ]);
    }
}
