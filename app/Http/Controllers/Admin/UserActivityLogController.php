<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserActivityLogController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        return Inertia::render('Users/ActivityLog', [
            'user' => $user,
            'activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->paginate($request->get('per_page', 20)),
        ]);
    }
}
