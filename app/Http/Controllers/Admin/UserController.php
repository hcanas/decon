<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'page' => 'nullable|numeric',
            'per_page' => 'nullable|numeric',
            'sort_field' => ['nullable', Rule::in(['name', 'email', 'access_level'])],
            'sort_order' => ['nullable', 'required_with:sortField', Rule::in(['asc', 'desc'])],
            'query' => 'nullable|max:255',
        ]);

        if ($validator->fails()) abort(404);

        $users = User::search($request->get('query'), function ($meilisearch, $query, $options) use ($request) {
            if (!empty($request->get('sort_field'))) {
                $options['sort'] = [$request->get('sort_field').':'.$request->get('sort_order')];
            }

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Admin/Users/Index', [
            'filters' => $request->query(),
            'users' => $users->paginate($request->get('per_page', 10))
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user) : RedirectResponse
    {
        $user->fill($request->validated())->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
