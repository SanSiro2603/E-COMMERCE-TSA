<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{

    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('pembeli.profile.show', compact('user'));
    }
   

    public function changePassword()
    {
        return view('pembeli.profile.change-password');
    }

    public function edit()
    {
    $user = Auth::user();
    return view('pembeli.profile.edit', compact('user'));
    }

public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|string',
        'birth_date' => 'nullable|date',
        'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);
    /** @var User $user */
    $user = Auth::user();

    // upload foto
    if ($request->hasFile('profile_photo')) {

        // hapus foto lama (optional)
        if ($user->profile_photo) {
            Storage::delete('public/' . $user->profile_photo);
        }

        $path = $request->file('profile_photo')->store('profile', 'public');

        $user->profile_photo = $path;
    }

    $user->update([
        'name' => $request->name,
        'phone' => $request->phone,
        'gender' => $request->gender,
        'birth_date' => $request->birth_date,
        'profile_photo' => $user->profile_photo
    ]);

    return redirect()->route('pembeli.profile.show')
        ->with('success', 'Profil berhasil diupdate');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);
    /** @var User $user */
    $user = Auth::user();

    // cek password lama
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama salah']);
    }

    // update password
    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return redirect()->route('pembeli.profile.show')
        ->with('success', 'Password berhasil diubah');
}
}