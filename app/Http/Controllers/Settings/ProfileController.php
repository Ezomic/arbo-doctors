<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     *
     * Read-only: identity fields are synced from the Identity service on
     * login, not editable locally.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile');
    }
}
