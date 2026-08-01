<?php

namespace App\Helpers;

class AgentHelper
{
    /**
     * Parse User-Agent string to extract device, OS, and browser details.
     *
     * @param string|null $userAgent
     * @return array{device_type: string, device_name: string, operating_system: string, browser: string}
     */
    public static function parse(?string $userAgent): array
    {
        $ua = $userAgent ?? request()->userAgent() ?? '';

        return [
            'device_type'      => static::detectDeviceType($ua),
            'device_name'      => static::detectDeviceName($ua),
            'operating_system' => static::detectOS($ua),
            'browser'          => static::detectBrowser($ua),
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // Device Type
    // ─────────────────────────────────────────────────────────────

    public static function detectDeviceType(string $ua): string
    {
        // Tablet first (must check before generic Mobile)
        if (preg_match('/(?:tablet|ipad|playbook|silk|kfapwi|nexus 7|nexus 10|gt-p|sm-t|sm-x)/i', $ua)) {
            return 'Tablet';
        }

        // Android without "Mobile" → tablet browser mode
        if (preg_match('/android/i', $ua) && !preg_match('/mobile/i', $ua)) {
            return 'Tablet';
        }

        if (preg_match('/(?:mobile|iphone|ipod|android|blackberry|IEMobile|Kindle|webOS|Fennec|Opera Mini|Opera Mobi)/i', $ua)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    // ─────────────────────────────────────────────────────────────
    // Device Brand & Model
    // Read the model segment directly from: Linux; Android X.X; <MODEL> Build/...
    // ─────────────────────────────────────────────────────────────

    public static function detectDeviceName(string $ua): string
    {
        $request = request();

        // Tier 1: Client Hints API / JS Cookie (Exact High-Entropy Device Model)
        $clientDevice = $request->header('X-Device-Model')
            ?? ($_COOKIE['admin_device_name'] ?? null)
            ?? $request->cookie('admin_device_name');

        if (filled($clientDevice) && is_string($clientDevice)) {
            $cleaned = trim(urldecode($clientDevice));
            if (strlen($cleaned) > 1 && strlen($cleaned) < 50 && $cleaned !== 'Unknown') {
                return $cleaned;
            }
        }

        // Tier 2: Apple Devices
        if (stripos($ua, 'iPhone') !== false)    return 'iPhone';
        if (stripos($ua, 'iPad') !== false)       return 'iPad';
        if (stripos($ua, 'Macintosh') !== false)  return 'Mac';

        // Tier 2 & 3: Android Model Segment Extractor (Linux; Android X.X; <MODEL> Build/...)
        $androidModel = null;
        if (preg_match('/Android[^;]*;\s*([^;)]+)\s*Build/i', $ua, $m)) {
            $androidModel = trim($m[1]);
        }

        // Infinix
        if (preg_match('/(?:Infinix|X6\d{3}|X6\d{2}[A-Z]|X5\d{3})/i', $ua)) {
            if ($androidModel) {
                return stripos($androidModel, 'Infinix') !== false ? $androidModel : 'Infinix ' . $androidModel;
            }
            return 'Infinix Device';
        }

        // Tecno
        if (preg_match('/(?:TECNO|CK\d|LH\d|AD\d|BF\d)/i', $ua)) {
            if ($androidModel) {
                return stripos($androidModel, 'TECNO') !== false ? $androidModel : 'TECNO ' . $androidModel;
            }
            return 'TECNO Device';
        }

        // Itel
        if (preg_match('/(?:itel|A6\d|L6\d)/i', $ua)) {
            if ($androidModel) {
                return stripos($androidModel, 'itel') !== false ? $androidModel : 'Itel ' . $androidModel;
            }
            return 'Itel Device';
        }

        // Xiaomi / Redmi / POCO
        if (preg_match('/(?:Redmi|POCO|Xiaomi|Mi\s|220\d|210\d|230\d|240\d|250\d|M210|2201)/i', $ua)) {
            if (preg_match('/(Redmi\s*[\w\s]+|POCO\s*[\w\s]+|Xiaomi\s*[\w\s]+|Mi\s*[\w\s]+)/i', $ua, $xm)) {
                return trim($xm[1]);
            }
            if ($androidModel) return 'Xiaomi/Redmi (' . $androidModel . ')';
            return 'Xiaomi / POCO';
        }

        // Samsung
        if (preg_match('/(?:Samsung|SM-[A-Z0-9]+)/i', $ua)) {
            if (preg_match('/(SM-[A-Z0-9]+)/i', $ua, $sm)) {
                return 'Samsung (' . $sm[1] . ')';
            }
            if ($androidModel) return 'Samsung ' . $androidModel;
            return 'Samsung Device';
        }

        // Oppo
        if (preg_match('/(?:OPPO|CPH\d{4}|PDR\d{3})/i', $ua)) {
            if ($androidModel) return 'Oppo ' . $androidModel;
            return 'Oppo Device';
        }

        // Vivo
        if (preg_match('/(?:vivo|V2\d{3}|V2\d{2}[A-Z])/i', $ua)) {
            if ($androidModel) return 'Vivo ' . $androidModel;
            return 'Vivo Device';
        }

        // Realme
        if (preg_match('/(?:realme|RMX\d{4})/i', $ua)) {
            if ($androidModel) return 'Realme ' . $androidModel;
            return 'Realme Device';
        }

        // Google Pixel
        if (preg_match('/(?:Pixel\s*[\d\w]*)/i', $ua, $m)) {
            return 'Google ' . trim($m[0]);
        }

        // Asus / ROG
        if (preg_match('/Asus|ROG/i', $ua)) {
            if ($androidModel) return 'Asus ' . $androidModel;
            return 'Asus Device';
        }

        // Sony
        if (preg_match('/Sony|SO-\d|Xperia/i', $ua)) {
            if ($androidModel) return 'Sony ' . $androidModel;
            return 'Sony Device';
        }

        // Huawei / Honor
        if (preg_match('/HUAWEI|HONOR|VOG-L|ELE-L/i', $ua)) {
            if ($androidModel) return 'Huawei/Honor ' . $androidModel;
            return 'Huawei Device';
        }

        // Generic Android model fallback
        if ($androidModel && strlen($androidModel) > 2 && strlen($androidModel) < 35) {
            return $androidModel;
        }

        if (preg_match('/Android/i', $ua))        return 'Android Device';

        // Desktop
        if (stripos($ua, 'Windows') !== false)    return 'Windows PC';
        if (stripos($ua, 'Linux') !== false)      return 'Linux PC';

        return 'Unknown Device';
    }

    // ─────────────────────────────────────────────────────────────

    // OS Version — reads directly from UA, no assumptions
    // ─────────────────────────────────────────────────────────────

    public static function detectOS(string $ua): string
    {
        if (preg_match('/Android/i', $ua))          return 'Android';
        if (preg_match('/iPhone\s*OS/i', $ua))      return 'iOS';
        if (preg_match('/CPU\s*OS/i', $ua))         return 'iPadOS';
        if (preg_match('/Mac\s*OS/i', $ua))         return 'macOS';
        if (stripos($ua, 'Windows') !== false)       return 'Windows';
        if (stripos($ua, 'Linux') !== false)         return 'Linux';

        return 'Unknown OS';
    }


    // ─────────────────────────────────────────────────────────────
    // Browser Detection
    // ─────────────────────────────────────────────────────────────

    public static function detectBrowser(string $ua): string
    {
        // Check specific / niche browsers first to avoid Chrome-family false positives
        if (preg_match('/SamsungBrowser\/([\d.]+)/i', $ua, $m)) {
            return 'Samsung Internet ' . $m[1];
        }
        if (preg_match('/(?:OPR|OPX)\/([\d.]+)/i', $ua, $m)) {
            return 'Opera ' . $m[1];
        }
        if (preg_match('/Edg\/([\d.]+)/i', $ua, $m)) {
            return 'Microsoft Edge ' . $m[1];
        }
        if (preg_match('/Firefox\/([\d.]+)/i', $ua, $m)) {
            return 'Mozilla Firefox ' . $m[1];
        }
        if (preg_match('/UCBrowser\/([\d.]+)/i', $ua, $m)) {
            return 'UC Browser ' . $m[1];
        }
        if (preg_match('/YaBrowser\/([\d.]+)/i', $ua, $m)) {
            return 'Yandex Browser ' . $m[1];
        }
        // Chrome — must come after Edge/Opera (both embed "Chrome/")
        if (preg_match('/Chrome\/([\d.]+)/i', $ua, $m)) {
            return 'Google Chrome ' . $m[1];
        }
        // Safari — only if no Chrome
        if (preg_match('/Version\/([\d.]+).*Safari/i', $ua, $m)) {
            return 'Safari ' . $m[1];
        }
        if (stripos($ua, 'Safari') !== false) {
            return 'Safari';
        }
        if (preg_match('/Opera\/([\d.]+)/i', $ua, $m)) {
            return 'Opera ' . $m[1];
        }

        return 'Browser Lainnya';
    }
}
