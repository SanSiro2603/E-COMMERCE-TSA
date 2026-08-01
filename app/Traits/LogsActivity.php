<?php

namespace App\Traits;

use App\Helpers\AgentHelper;
use App\Helpers\LogHelper;
use App\Models\AdminLog;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait LogsActivity
{
    /**
     * Boot the trait to listen for Eloquent model events.
     */
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            static::recordActivity($model, 'created');
        });

        static::updated(function ($model) {
            static::recordActivity($model, 'updated');
        });

        static::deleted(function ($model) {
            static::recordActivity($model, 'deleted');
        });
    }

    /**
     * Record an activity log entry to the admin_logs table.
     */
    protected static function recordActivity($model, string $event): void
    {
        $user = Auth::user();

        // Only log if triggered by an authenticated admin / super_admin
        if (! $user || ! in_array($user->role, ['admin', 'super_admin'], true)) {
            return;
        }

        $module = static::resolveModuleName($model);
        $sensitiveFields = array_merge(
            ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes', 'token', 'created_at', 'updated_at', 'deleted_at'],
            property_exists($model, 'logExcept') ? (array) $model->logExcept : []
        );

        $action = $event;
        $severity = 'info';
        $oldValues = null;
        $newValues = null;
        $description = '';

        if ($event === 'created') {
            $rawNew = $model->getAttributes();
            $newValues = static::filterAttributes($rawNew, $sensitiveFields);
            $displayName = static::getModelDisplayName($model);
            $description = "{$module} {$displayName} berhasil ditambahkan";
        } elseif ($event === 'updated') {
            // SPECIAL HANDLING FOR ORDER STATUS CHANGE
            if ($model instanceof Order && $model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status') ?? 'unknown';
                $newStatus = $model->status;
                $orderId = $model->order_number ?? $model->id;

                $action = 'status_changed';
                $severity = in_array(strtolower((string) $newStatus), ['cancelled', 'dibatalkan'], true) ? 'warning' : 'info';
                $oldValues = ['status' => $oldStatus];
                $newValues = ['status' => $newStatus];
                $description = "Status pesanan #{$orderId} diubah dari '{$oldStatus}' menjadi '{$newStatus}' oleh {$user->name}";
            } else {
                $rawChanges = $model->getChanges();
                // If getChanges() is empty (e.g. during sync), fallback to dirty attributes
                if (empty($rawChanges)) {
                    $rawChanges = $model->getDirty();
                }

                $filteredNew = static::filterAttributes($rawChanges, $sensitiveFields);

                // If only ignored/sensitive fields changed, skip logging
                if (empty($filteredNew)) {
                    return;
                }

                $filteredOld = [];
                foreach (array_keys($filteredNew) as $key) {
                    $filteredOld[$key] = $model->getOriginal($key);
                }

                $oldValues = $filteredOld;
                $newValues = $filteredNew;
                $displayName = static::getModelDisplayName($model);
                $diffText = static::formatChangesDiff($filteredOld, $filteredNew);

                $description = "{$module} {$displayName} diubah: {$diffText}";
            }
        } elseif ($event === 'deleted') {
            $severity = 'warning';
            $oldValues = static::filterAttributes($model->getAttributes(), $sensitiveFields);
            $displayName = static::getModelDisplayName($model);
            $description = "{$module} {$displayName} berhasil dihapus";
        }

        $userAgent = request()->userAgent();
        $agentInfo = AgentHelper::parse($userAgent);
        $gps = LogHelper::getGpsLocation();

        AdminLog::create([
            'user_id'          => $user->id,
            'admin_name'       => $user->name,
            'admin_email'      => $user->email,
            'action'           => $action,
            'module'           => $module,
            'model_id'         => $model->getKey(),
            'description'      => $description,
            'old_values'       => $oldValues,
            'new_values'       => $newValues,
            'severity'         => $severity,
            'latitude'         => $gps['latitude'],
            'longitude'        => $gps['longitude'],
            'ip_address'       => LogHelper::getRealIp(),
            'device_type'      => $agentInfo['device_type'],
            'device_name'      => $agentInfo['device_name'],
            'operating_system' => $agentInfo['operating_system'],
            'browser'          => $agentInfo['browser'],
            'user_agent'       => $userAgent,
        ]);
    }

    /**
     * Resolve human readable module name for the model.
     */
    protected static function resolveModuleName($model): string
    {
        if (method_exists($model, 'getLogModuleName')) {
            return $model->getLogModuleName();
        }

        if (property_exists($model, 'logModuleName') && filled($model->logModuleName)) {
            return $model->logModuleName;
        }

        if ($model instanceof Category) {
            return $model->parent_id ? 'Sub Kategori' : 'Kategori';
        }

        if ($model instanceof Product) {
            return 'Produk';
        }

        if ($model instanceof Order) {
            return 'Pesanan';
        }

        $className = class_basename($model);
        if (Str::startsWith($className, ['Landing', 'Home'])) {
            return 'CMS Landing Page';
        }

        return $className;
    }

    /**
     * Get human-readable display name for the model instance.
     */
    protected static function getModelDisplayName($model): string
    {
        if ($model instanceof Order) {
            return '#' . ($model->order_number ?? $model->id);
        }

        if (isset($model->name) && filled($model->name)) {
            return "'{$model->name}'";
        }

        if (isset($model->title) && filled($model->title)) {
            return "'{$model->title}'";
        }

        if (isset($model->title_id) && filled($model->title_id)) {
            return "'{$model->title_id}'";
        }

        if (isset($model->title_en) && filled($model->title_en)) {
            return "'{$model->title_en}'";
        }

        if (isset($model->key) && filled($model->key)) {
            return "'{$model->key}'";
        }

        return '#' . $model->getKey();
    }

    /**
     * Filter out sensitive or blacklisted fields from values array.
     */
    protected static function filterAttributes(array $attributes, array $sensitiveFields): array
    {
        $filtered = [];
        foreach ($attributes as $key => $value) {
            if (! in_array($key, $sensitiveFields, true)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }

    /**
     * Format per-field diff into readable description text.
     */
    protected static function formatChangesDiff(array $old, array $new): string
    {
        $diffs = [];
        foreach ($new as $key => $newVal) {
            $oldVal = $old[$key] ?? null;
            $oldFormatted = static::formatValueForDiff($key, $oldVal);
            $newFormatted = static::formatValueForDiff($key, $newVal);

            $label = Str::headline($key);
            $diffs[] = "{$label} dari {$oldFormatted} menjadi {$newFormatted}";
        }

        return implode(', ', $diffs);
    }

    /**
     * Format attribute value cleanly for diff description.
     */
    protected static function formatValueForDiff(string $key, mixed $value): string
    {
        if (is_null($value)) {
            return 'kosong';
        }

        if (is_bool($value)) {
            return $value ? 'Aktif/Ya' : 'Nonaktif/Tidak';
        }

        if (in_array(strtolower($key), ['price', 'subtotal', 'shipping_cost', 'grand_total'], true) && is_numeric($value)) {
            return 'Rp' . number_format((float) $value, 0, ',', '.');
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return "'{$value}'";
    }
}
