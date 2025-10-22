<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses;
        return view('frontend.addresses', compact('addresses'));
    }

    public function create()
    {
        return view('frontend.address-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
        ]);

        $validated['user_id'] = Auth::id();
        Address::create($validated);

        return redirect()->route('addresses.index')
            ->with('success', 'Address added successfully.');
    }

    public function edit(Address $address)
    {
        if ($address->user_id != Auth::id()) {
            abort(403);
        }
        return view('frontend.address-edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id != Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
        ]);

        $address->update($validated);
        return redirect()->route('addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id != Auth::id()) {
            abort(403);
        }
        $address->delete();
        return back()->with('success', 'Address deleted successfully.');
    }
}