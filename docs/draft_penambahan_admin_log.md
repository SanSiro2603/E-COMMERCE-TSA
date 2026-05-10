# Draft Perancangan Fitur Log Aktivitas Admin (Admin Log)

Dokumen ini berisi draft / rancangan teknis untuk menambahkan fitur pencatatan riwayat aktivitas (Activity Log) yang dilakukan oleh pengguna dengan role `admin` atau `super_admin`.

## 1. Perancangan Database (Model & Migration)

Dibutuhkan sebuah tabel baru untuk merekam riwayat aktivitas.

**Nama Tabel:** `admin_logs`  
**Nama Model:** `AdminLog`

**Struktur Tabel (Migration):**
```php
Schema::create('admin_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Admin yang melakukan aksi
    $table->string('action'); // Jenis aksi: create, update, delete, login, dll
    $table->text('description'); // Penjelasan: "Mengubah harga produk Whiskas"
    $table->string('ip_address')->nullable(); // IP Address admin
    $table->text('user_agent')->nullable(); // Browser / Device yang digunakan
    $table->timestamps(); // Kapan aksi dilakukan
});
```

**Model `app/Models/AdminLog.php`:**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description', 'ip_address', 'user_agent'];

    // Relasi balik ke Admin
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 2. Pembuatan Helper / Service Pencatatan

Agar pencatatan log mudah dipanggil dari berbagai Controller, lebih baik dibuatkan sebuah *Helper* (atau diletakkan sebagai fungsi statis pada model).

```php
namespace App\Helpers;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function record($action, $description)
    {
        if (Auth::check()) {
            AdminLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        }
    }
}
```
*(Contoh Penggunaan di `ProductController@update`)*: 
`LogHelper::record('Update Produk', "Mengubah data produk dengan ID {$product->id}");`

---

## 3. Penambahan Controller (Log Viewer)

Super Admin membutuhkan antarmuka untuk melihat aktivitas ini. 

**Controller:** `app/Http/Controllers/SuperAdmin/AdminLogController.php`
```php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminLog::with('user')->latest();

        // Fitur pencarian berdasarkan nama admin atau aksi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('action', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        }

        $logs = $query->paginate(15);
        return view('superadmin.logs.index', compact('logs'));
    }
}
```

---

## 4. Penambahan Routing `web.php`

Rute ini akan dilindungi khusus untuk Super Admin (digabungkan di grup middleware superadmin).

```php
// Di dalam rute group 'superadmin.'
Route::get('/activity-logs', [App\Http\Controllers\SuperAdmin\AdminLogController::class, 'index'])
       ->name('logs.index');
```

---

## 5. Perancangan User Interface (View)

Membuat fle tampilan tabel logs agar Super Admin bisa memantau pergerakan admin bawahannya.

**File:** `resources/views/superadmin/logs/index.blade.php`

Elemen UI yang harus ada:
1. **Form Filter/Pencarian**: Pencarian by tanggal atau kata kunci.
2. **Tabel Data**: Menampilkan elemen `Waktu (created_at)`, `Admin (user->name)`, `Aktivitas (action)`, `Deskripsi (description)`, `IP Address`.
3. **Paginasi**: Menghindari *load* data berlebih.

---

## Ringkasan Alur Kerja

1. **TRIGGER**: Admin X melakukan penghapusan produk.
2. **RECORD**: Fungsi hapus di *Controller* memanggil `LogHelper::record('Menghapus Produk', 'Menghapus dry food kucing')`.
3. **SAVE**: Mengisi data di *Database* (siapa yang menghapus, rincian aktivitas, dan metadata jaringan).
4. **VIEW**: Super Admin masuk ke menu "Log Aktivitas" di Dashboard untuk melihat detail aktivitas dari Admin X.
