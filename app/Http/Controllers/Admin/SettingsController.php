<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $customerLoginEnabled = SystemSetting::isEnabled('customer_login_enabled');

        return view('admin.settings.index', compact('customerLoginEnabled'));
    }

    public function update(Request $request)
    {
        $status = $request->boolean('customer_login_enabled') ? '1' : '0';

        SystemSetting::updateOrCreate(
            ['key' => 'customer_login_enabled'],
            ['value' => $status]
        );

        return back()->with('success', 'Akses login customer berhasil diperbarui!');
    }
}
