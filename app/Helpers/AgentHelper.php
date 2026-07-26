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
        // Apple
        if (stripos($ua, 'iPhone') !== false)    return 'iPhone';
        if (stripos($ua, 'iPad') !== false)       return 'iPad';
        if (stripos($ua, 'Macintosh') !== false)  return 'Mac';

        // Android (semua brand → cukup "Android Device")
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
