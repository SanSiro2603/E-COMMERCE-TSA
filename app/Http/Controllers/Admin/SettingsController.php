<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Ambil setting 'shopping_enabled', default '1' jika belum ada
        $setting = SystemSetting::where('key', 'shopping_enabled')->first();
        $shoppingEnabled = $setting ? $setting->value : '1';

        return view('admin.settings.index', compact('shoppingEnabled'));
    }

    public function update(Request $request)
    {
        // Validasi input (opsional, krn cuma toggle)
        $status = $request->has('shopping_enabled') ? '1' : '0';

        // Simpan ke database
        SystemSetting::updateOrCreate(
            ['key' => 'shopping_enabled'], // Cari key 
            ['value' => $status]           // Update valuenya
        );

        return back()->with('success', 'Status toko berhasil diperbarui!');
    }
}
