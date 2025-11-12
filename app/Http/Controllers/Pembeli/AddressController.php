<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('pembeli.alamat.index', compact('addresses'));
    }

    public function create()
    {
        return view('pembeli.alamat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'           => 'required|string|max:50',
            'recipient_name'  => 'required|string|max:255',
            'recipient_phone' => 'required|regex:/^08[0-9]{8,11}$/',
            'province_id'     => 'required',
            'province_name'   => 'required|string',
            'city_id'         => 'required',
            'city_name'       => 'required|string',
            'city_type'       => 'required|in:Kota,Kabupaten',
            'postal_code'     => 'nullable|string|max:10',
            'full_address'    => 'required|string|max:500',
            'is_default'      => 'sometimes|boolean',
        ]);

        $address = Auth::user()->addresses()->create($validated);

        if ($request->boolean('is_default')) {
            $address->setAsDefault();
        }

        return redirect()->route('pembeli.alamat.index')
            ->with('success', 'Alamat berhasil disimpan!');
    }

    public function edit(Address $alamat)
    {
        return view('pembeli.alamat.edit', compact('alamat'));
    }

    public function update(Request $request, Address $alamat)
    {
        $this->authorizeAddress($alamat);

        $validated = $request->validate([
            'label'           => 'required|string|max:50',
            'recipient_name'  => 'required|string|max:255',
            'recipient_phone' => 'required|regex:/^08[0-9]{8,11}$/',
            'province_id'     => 'required',
            'province_name'   => 'required|string',
            'city_id'         => 'required',
            'city_name'       => 'required|string',
            'city_type'       => 'required|in:Kota,Kabupaten',
            'postal_code'     => 'nullable|string|max:10',
            'full_address'    => 'required|string|max:500',
            'is_default'      => 'sometimes|boolean',
        ]);

        $alamat->update($validated);

        if ($request->boolean('is_default')) {
            $alamat->setAsDefault();
        }

        return redirect()->route('pembeli.alamat.index')
            ->with('success', 'Alamat diperbarui!');
    }

    public function destroy(Address $alamat)
    {
        $this->authorizeAddress($alamat);
        $alamat->delete();

        return back()->with('success', 'Alamat dihapus.');
    }

    public function setDefault(Address $alamat)
    {
        $this->authorizeAddress($alamat);
        $alamat->setAsDefault();

        return back()->with('success', 'Alamat utama diperbarui.');
    }

    protected function authorizeAddress($address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
    }
}