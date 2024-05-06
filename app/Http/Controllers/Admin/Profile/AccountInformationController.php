<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AccountInformationController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        return Inertia::render('Profile/ProfileInformationForm', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        DB::beginTransaction();

        $request->user()->save();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'category' => 'account',
            'description' => 'Updated account information.',
        ]);

        DB::commit();

        return Redirect::route('profile.edit');
    }
}
