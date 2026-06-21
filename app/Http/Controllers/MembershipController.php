<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreMembershipRequest;
use App\Http\Requests\Admin\UpdateMembershipStatusRequest;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $memberships = Membership::query()
            ->with([
                'user' => function ($q) {
                    $q->select(['id', 'name', 'phone']);
                },
                'package' => function ($q) {
                    $q->select(['id', 'name', 'price', 'duration_days']);
                },
            ])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where(function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%")
                            ->orWhere('phone', 'ilike', "%{$search}%");
                    });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();

        $users = User::where('role', 'member')->select(['id', 'name', 'phone'])->get();
        $packages = Package::select(['id', 'name', 'price', 'duration_days'])->get();

        return Inertia::render('Memberships/Index', [
            'memberships' => $memberships,
            'users' => $users,
            'packages' => $packages,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMembershipRequest $request)
    {
        Membership::create($request->validated());

        return redirect()->back()->with('success', 'Membership subscription generated as pending');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembershipStatusRequest $request, Membership $membership)
    {
        $data = ['status' => $request->status];
        if ($request->status === 'active') {
            $package = $membership->package;
            $data['start_date'] = Carbon::now()->toDateString();
            $data['end_date'] = Carbon::now()->addDays($package->duration_days)->toDateString();
        } elseif ($request->status === 'pending') {
            $date['start_date'] = null;
            $date['end_date'] = null;
        }

        $membership->update($data);

        return redirect()->back()->with('success', 'Membership plan status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership)
    {
        $membership->delete();

        return redirect()->back()->with('success', 'Membership subscription canceled and removed');
    }
}
