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
        // Extract the raw model string from Android UA (most accurate source)
        // Typical: Mozilla/5.0 (Linux; Android 15; Pixel 9 Build/AP4A...) ...
        //          Mozilla/5.0 (Linux; Android 14; SM-S918B Build/...) ...
        $model = '';
        if (preg_match('/Linux;\s*Android\s*[\d.]+;\s*([^;)]+?)(?:\s*Build|\s*\))/i', $ua, $m)) {
            $model = trim($m[1]);
        }

        // ── Xiaomi family ──
        if (preg_match('/\bPOCO\s+([A-Za-z0-9]+)/i', $ua, $m)) {
            return 'POCO ' . strtoupper($m[1]);
        }
        if (preg_match('/\bRedmi\s+([A-Za-z0-9\s]+?)(?:\s*Build|\s*;|\s*\))/i', $ua, $m)) {
            return 'Redmi ' . trim($m[1]);
        }

        // Xiaomi numeric model codes found in $model segment (from Linux; Android X; <model> Build)
        // Examples: M2101K6G (Redmi Note 10), 23116PN5BC (POCO X6 Pro), 2201116PG (Redmi Note 11)
        // All Xiaomi codes share: alphanumeric-only, 8-12 chars, mix of digits & letters
        if ($model !== '' && preg_match('/^[M]?\d{3,5}[A-Z0-9]{3,8}$/i', $model) && strlen($model) >= 7 && strlen($model) <= 12) {
            if (preg_match('/^2[3-5]/i', $model)) {
                return 'Xiaomi / POCO (' . $model . ')';
            }
            return 'Xiaomi (' . $model . ')';
        }

        if (stripos($ua, 'Xiaomi') !== false || stripos($ua, 'HM NOTE') !== false) {
            return $model !== '' ? 'Xiaomi ' . $model : 'Xiaomi Device';
        }

        // ── Samsung ──
        // SM-AXXX series: phones; SM-TXXX / SM-XXXX series: tablets
        if (preg_match('/\b(SM-[A-Z][0-9]{3,4}[A-Z0-9]*)\b/i', $ua, $m)) {
            return 'Samsung Galaxy (' . strtoupper($m[1]) . ')';
        }
        if (preg_match('/\bSamsung\s+([A-Za-z0-9\s\-]+?)(?:\s*Build|\s*;)/i', $ua, $m)) {
            return 'Samsung Galaxy ' . trim($m[1]);
        }
        if (stripos($ua, 'Samsung') !== false) {
            return $model !== '' ? 'Samsung Galaxy ' . $model : 'Samsung Galaxy';
        }

        // ── Apple ──
        if (stripos($ua, 'iPhone') !== false) {
            if (preg_match('/iPhone OS ([\d_]+)/i', $ua, $m)) {
                $ver = str_replace('_', '.', $m[1]);
                return 'iPhone (iOS ' . $ver . ')';
            }
            return 'iPhone';
        }
        if (stripos($ua, 'iPad') !== false) {
            if (preg_match('/CPU OS ([\d_]+)/i', $ua, $m)) {
                $ver = str_replace('_', '.', $m[1]);
                return 'iPad (iPadOS ' . $ver . ')';
            }
            return 'iPad';
        }
        if (stripos($ua, 'Macintosh') !== false) {
            return 'Mac / MacBook';
        }

        // ── OPPO ──
        if (preg_match('/\b(CPH[0-9]{4}[A-Z0-9]*|PHK[0-9A-Z]+|PGHM[0-9A-Z]+)\b/i', $ua, $m)) {
            return 'Oppo (' . strtoupper($m[1]) . ')';
        }
        if (stripos($ua, 'Oppo') !== false) {
            return $model !== '' ? 'Oppo ' . $model : 'Oppo Device';
        }

        // ── Vivo ──
        if (preg_match('/\b(V[0-9]{4}[A-Z]{1,2}|V[0-9]{4}BA|V[0-9]{4}T)\b/i', $ua, $m)) {
            return 'Vivo (' . strtoupper($m[1]) . ')';
        }
        if (stripos($ua, 'Vivo') !== false) {
            return $model !== '' ? 'Vivo ' . $model : 'Vivo Device';
        }

        // ── Realme ──
        if (preg_match('/\b(RMX[0-9]{4}[A-Z0-9]*)\b/i', $ua, $m)) {
            return 'Realme (' . strtoupper($m[1]) . ')';
        }
        if (stripos($ua, 'Realme') !== false) {
            return $model !== '' ? 'Realme ' . $model : 'Realme Device';
        }

        // ── Infinix ──
        if (preg_match('/\b(X[0-9]{3,4}[A-Z]?)\b/', $ua, $m)) {
            // Avoid matching "X11" etc. from browser tokens: require it inside the model segment
            if ($model !== '' && stripos($model, $m[1]) !== false) {
                return 'Infinix (' . strtoupper($m[1]) . ')';
            }
        }
        if (stripos($ua, 'Infinix') !== false) {
            return $model !== '' ? 'Infinix ' . $model : 'Infinix Device';
        }

        // ── Google Pixel ──
        if (preg_match('/\b(Pixel\s+[0-9][a-zA-Z\s]*?)(?:\s*Build|\s*;|\s*\))/i', $ua, $m)) {
            return 'Google ' . trim($m[1]);
        }

        // ── Generic Android: use extracted model string ──
        if ($model !== '') {
            return 'Android Device (' . $model . ')';
        }

        // ── Windows PC ──
        if (stripos($ua, 'Windows NT 10.0') !== false) {
            return 'Windows PC (Windows 10/11)';
        }
        if (stripos($ua, 'Windows NT 6.3') !== false) {
            return 'Windows PC (Windows 8.1)';
        }
        if (stripos($ua, 'Windows NT 6.1') !== false) {
            return 'Windows PC (Windows 7)';
        }
        if (stripos($ua, 'Windows') !== false) {
            return 'Windows PC';
        }

        if (stripos($ua, 'Linux') !== false) {
            return 'Linux PC';
        }

        return 'Unknown Device';
    }

    // ─────────────────────────────────────────────────────────────
    // OS Version — reads directly from UA, no assumptions
    // ─────────────────────────────────────────────────────────────

    public static function detectOS(string $ua): string
    {
        // Android — exact version from UA string
        if (preg_match('/Android\s*([\d.]+)/i', $ua, $m)) {
            $version = trim($m[1], '.');
            return 'Android ' . $version;
        }

        // iOS / iPhone
        if (preg_match('/iPhone\s*OS\s*([\d_]+)/i', $ua, $m)) {
            return 'iOS ' . str_replace('_', '.', $m[1]);
        }

        // iPadOS
        if (preg_match('/CPU\s*OS\s*([\d_]+)/i', $ua, $m)) {
            return 'iPadOS ' . str_replace('_', '.', $m[1]);
        }

        // macOS
        if (preg_match('/Mac\s*OS\s*X\s*([\d_.]+)/i', $ua, $m)) {
            $ver = str_replace('_', '.', $m[1]);
            return 'macOS ' . $ver;
        }

        // Windows — map NT version to marketing name
        if (preg_match('/Windows\s*NT\s*([\d.]+)/i', $ua, $m)) {
            return match($m[1]) {
                '10.0' => 'Windows 10 / 11',
                '6.3'  => 'Windows 8.1',
                '6.2'  => 'Windows 8',
                '6.1'  => 'Windows 7',
                '6.0'  => 'Windows Vista',
                '5.1', '5.2' => 'Windows XP',
                default => 'Windows (NT ' . $m[1] . ')',
            };
        }

        if (stripos($ua, 'Linux') !== false) {
            return 'Linux';
        }

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
