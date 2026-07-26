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
            'device_type' => static::detectDeviceType($ua),
            'device_name' => static::detectDeviceName($ua),
            'operating_system' => static::detectOS($ua),
            'browser' => static::detectBrowser($ua),
        ];
    }

    public static function detectDeviceType(string $ua): string
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            return 'Tablet';
        }

        if (preg_match('/(mobile|iphone|ipod|android|blackberry|IEMobile|Kindle|NetFront|Silk-Accelerated|hpwOS|webOS|Fennec|Minimo|Opera Mini|Opera Mobi|PalmOS|Maemo|Tizen|Vivaldi)/i', $ua)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    public static function detectDeviceName(string $ua): string
    {
        if (preg_match('/(POCO[^\s;]+)/i', $ua, $matches)) {
            return 'POCO (' . str_replace('_', ' ', $matches[1]) . ')';
        }
        if (preg_match('/(Redmi[^\s;]+)/i', $ua, $matches)) {
            return 'Xiaomi (' . str_replace('_', ' ', $matches[1]) . ')';
        }
        if (preg_match('/(Mi [^\s;]+|Mi-[^\s;]+)/i', $ua, $matches)) {
            return 'Xiaomi (' . str_replace('_', ' ', $matches[1]) . ')';
        }
        // Match Xiaomi model codes (e.g. M2101K6G, 2201116PG, M2007J20CG, etc.)
        if (preg_match('/(M[0-9]{4,8}[A-Z0-9]+|2[0-9]{7,11}[A-Z]+)/i', $ua, $matches)) {
            return 'Xiaomi (' . $matches[1] . ')';
        }
        if (stripos($ua, 'Xiaomi') !== false) {
            return 'Xiaomi Device';
        }

        // 2. Samsung
        if (preg_match('/(SM-[A-Z0-9]+)/i', $ua, $matches)) {
            return 'Samsung Galaxy (' . $matches[1] . ')';
        }
        if (stripos($ua, 'Samsung') !== false) {
            return 'Samsung Galaxy';
        }

        // 3. Apple
        if (stripos($ua, 'iPhone') !== false) {
            return 'iPhone';
        }
        if (stripos($ua, 'iPad') !== false) {
            return 'iPad';
        }
        if (stripos($ua, 'Macintosh') !== false || stripos($ua, 'Mac OS') !== false) {
            return 'Mac / MacBook';
        }

        // 4. Oppo / Vivo / Realme / Infinix
        if (preg_match('/(CPH[0-9]+|OPPO[^\s;]+)/i', $ua, $matches)) {
            return 'Oppo (' . $matches[1] . ')';
        }
        if (stripos($ua, 'Oppo') !== false) {
            return 'Oppo Device';
        }

        if (preg_match('/(V2[0-9]{3}[A-Z]?|V1[0-9]{3}[A-Z]?|vivo[^\s;]+)/i', $ua, $matches)) {
            return 'Vivo (' . $matches[1] . ')';
        }
        if (stripos($ua, 'Vivo') !== false) {
            return 'Vivo Device';
        }

        if (preg_match('/(RMX[0-9]+|realme[^\s;]+)/i', $ua, $matches)) {
            return 'Realme (' . $matches[1] . ')';
        }
        if (stripos($ua, 'Realme') !== false) {
            return 'Realme Device';
        }

        if (preg_match('/(X[0-9]{3,4}|Infinix[^\s;]+)/i', $ua, $matches)) {
            return 'Infinix (' . $matches[1] . ')';
        }
        if (stripos($ua, 'Infinix') !== false) {
            return 'Infinix Device';
        }

        // 5. General Android & Desktop
        if (preg_match('/Linux; Android ([0-9\.]+); ([^;\)]+)/i', $ua, $matches)) {
            $model = trim($matches[2]);
            if (!empty($model) && stripos($model, 'Build') === false) {
                return 'Android Device (' . $model . ')';
            }
            return 'Android Device';
        }

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

    public static function detectOS(string $ua): string
    {
        if (preg_match('/Android ([0-9\.]+)/i', $ua, $matches)) {
            return 'Android ' . $matches[1];
        }
        if (preg_match('/iPhone OS ([0-9_]+)/i', $ua, $matches)) {
            return 'iOS ' . str_replace('_', '.', $matches[1]);
        }
        if (preg_match('/CPU OS ([0-9_]+)/i', $ua, $matches)) {
            return 'iPadOS ' . str_replace('_', '.', $matches[1]);
        }
        if (preg_match('/Mac OS X ([0-9_]+)/i', $ua, $matches)) {
            return 'macOS ' . str_replace('_', '.', $matches[1]);
        }
        if (stripos($ua, 'Windows NT 10.0') !== false) {
            return 'Windows 10 / 11';
        }
        if (stripos($ua, 'Windows NT 6.3') !== false) {
            return 'Windows 8.1';
        }
        if (stripos($ua, 'Windows NT 6.1') !== false) {
            return 'Windows 7';
        }
        if (stripos($ua, 'Windows') !== false) {
            return 'Windows';
        }
        if (stripos($ua, 'Linux') !== false) {
            return 'Linux';
        }

        return 'Unknown OS';
    }

    public static function detectBrowser(string $ua): string
    {
        if (stripos($ua, 'Edg') !== false) {
            return 'Microsoft Edge';
        }
        if (stripos($ua, 'OPR') !== false || stripos($ua, 'Opera') !== false) {
            return 'Opera';
        }
        if (stripos($ua, 'SamsungBrowser') !== false) {
            return 'Samsung Internet';
        }
        if (stripos($ua, 'Chrome') !== false && stripos($ua, 'Chromium') === false) {
            return 'Google Chrome';
        }
        if (stripos($ua, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        }
        if (stripos($ua, 'Safari') !== false && stripos($ua, 'Chrome') === false) {
            return 'Safari';
        }

        return 'Browser Lainnya';
    }
}
