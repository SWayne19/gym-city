<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // user query with filter
        $users = User::query()
            ->where('role', 'member')
            ->select(['id', 'name', 'email', 'phone', 'created_at'])
            ->with([
                'memberships' => function ($q) {
                    $q->select(['id', 'user_id', 'package_id', 'status']);
                },
                'memberships.package' => function ($q) {
                    $q->select(['id', 'name']);
                },
            ])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->whereHas('memberships', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            })
            ->latest()
            ->get();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        User::create(array_merge(
            $request->validated(),
            [
                'password' => Hash::make('gymcity123'),
                'role' => 'member'
            ]
        ));

        return redirect()->back()->with('success', 'Member registered successfully');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return redirect()->back()->with('success', 'Member updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Member deleted successfully');
    }
}
