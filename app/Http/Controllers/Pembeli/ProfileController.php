<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        return view('pembeli.profile.show', compact('user'));
    }
   

    public function changePassword()
    {
        return view('pembeli.profile.change-password');
    }

}