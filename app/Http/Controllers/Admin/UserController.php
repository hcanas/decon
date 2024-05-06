<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : Response
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'nullable|numeric',
            'keyword' => 'nullable|max:255',
            'only' => ['nullable', Rule::in(['admin', 'regular'])],
        ]);

        if ($validator->fails()) abort(404);

        $users = User::search($request->get('keyword'), function ($meilisearch, $query, $options) use ($validator) {
            if (!empty($validator->validated()['only'])) {
                $options['filter'] = 'access_level = '.$validator->validated()['only'];
            }

            $options['sort'] = ['updated_at:desc'];

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Users/Index', [
            'filters' => $request->query(),
            'users' => $users->paginate($request->get('per_page', 20))
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'access_level' => $user->access_level,
                    'status' => $user->status,
                ]),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user) : RedirectResponse
    {
        $user->fill($request->validated());

        if ($user->isDirty('access_level')) {
            $message = 'Set access level of '.$user->email.' to '.$user->access_level.'.';
        } elseif ($user->isDirty('status')) {
            $message = 'Changed account status of '.$user->email.' to '.$user->status.'.';
        }

        if (isset($message)) {
            DB::beginTransaction();

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'category' => 'users',
                'description' => $message,
            ]);

            $user->save();

            DB::commit();
        } else {
            $user->save();
        }


        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->id === $user->id) abort(403);

        if ($user->activityLog()->where('category', '!=', 'account')->count()) abort(403);

        DB::beginTransaction();

        $user->activityLog()->delete();

        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'category' => 'users',
            'description' => 'Deleted user account of '.$user->email.'.',
        ]);

        DB::commit();

        return response()->json('', 204);
    }
}
