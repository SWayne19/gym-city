<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StorePackageRequest;
use App\Http\Requests\Admin\StorePaymentRequest;
use App\Models\Membership;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->with([
                'membership.user' => function ($q) {
                    $q->select(['id', 'name', 'phone']);
                },
                'membership.package' => function ($q) {
                    $q->select(['id', 'name']);
                },
            ])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('membership.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();

        $pendingMemberships = Membership::with(['user', 'package'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return Inertia::render('Payments/Index', [
            'payments' => $payments,
            'pendingMemberships' => $pendingMemberships,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StorePaymentRequest $request)
    // {
    //     Payment::create(array_merge(
    //         $request->validated(),
    //         [
    //             'status' => 'paid',
    //             'paid_at' => Carbon::now(),
    //         ]
    //     ));

    //     return redirect()->back()->with('success', 'Payment collected successfully. Membership is now activated!');
    // }

    public function store(StorePaymentRequest $request)
{
    try {
        // Validation အောင်မြင်တဲ့ ဒေတာတွေကို ရယူမယ်
        $validatedData = $request->validated();

        // 💡 ခြေရာခံရန်အတွက် ယာယီ Log ထုတ်ကြည့်ခြင်း
        \Log::info('Attempting to create payment:', $validatedData);

        // Payment ဖန်တီးခြင်း
        $payment = Payment::create([
            'membership_id' => $validatedData['membership_id'],
            'amount'        => $validatedData['amount'],
            'payment_method'=> $validatedData['payment_method'],
            'status'        => 'paid',
            'paid_at'       => \Carbon\Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Payment collected successfully. Membership is now activated!');

    } catch (\Exception $e) {
        // 🚨 Error တက်လာရင် အောက်က Log ဖိုင်ထဲကို အတင်း ရေးခိုင်းမယ်
        \Log::error('Payment Store Failed: ' . $e->getMessage());
        
        // Frontend ကို Error message အမှန်အတိုင်း ပြန်တွန်းလွှတ်မယ်
        return redirect()->back()->withErrors(['membership_id' => 'System Query Error: ' . $e->getMessage()]);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->back()->with('success', 'Payment invoice record removed.');
    }
}
