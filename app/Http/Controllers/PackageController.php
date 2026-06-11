<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StorePackageRequest;
use App\Http\Requests\Admin\UpdatePackageRequest;
use App\Models\Package;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $packages = Package::query()
            ->select(['id', 'name', 'price', 'duration_days', 'description'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->durction, function ($query, $duration) {
                switch ($duration) {
                    case 'short':
                        $query->where('duration_days', '<=', 30);
                        break;
                    case 'medium':
                        $query->whereBetween('duration_days', [31, 180]);
                        break;
                    case 'long':
                        $query->where('duration_days', '>', 180);
                        break;
                }
            })
            ->latest()
            ->get();
        return Inertia::render('Packages/Index', [
            'packages' => $packages,
            'filters' => $request->only(['search', 'duration'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request)
    {
        Package::create($request->validated());

        return redirect()->back()->with('success', 'Package created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        $package->update($request->validated());

        return redirect()->back()->with('success', 'Package updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        if ($package->memberships()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete package with active memberships');
        }

        $package->delete();

        return redirect()->back()->with('success', 'Package deleted successfully');
    }
}
