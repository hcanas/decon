<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Profile/ActivityLog', [
            'activities' => ActivityLog::where('user_id', $request->user()->id)
                ->latest()
                ->paginate($request->get('per_page', 20)),
        ]);
    }
}
