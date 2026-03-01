<?php

namespace App\Services;

class DeviceParserService
{
    /**
     * Parse user agent string to extract browser, platform, device type
     */
    public static function parse(string $userAgent): array
    {
        return [
            'browser' => self::getBrowser($userAgent),
            'platform' => self::getPlatform($userAgent),
            'device_type' => self::getDeviceType($userAgent),
        ];
    }

    /**
     * Detect browser from User-Agent
     */
    private static function getBrowser(string $userAgent): string
    {
        if (stripos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (stripos($userAgent, 'ChromeWebStore') !== false || stripos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (stripos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (stripos($userAgent, 'OPR') !== false || stripos($userAgent, 'Opera') !== false) {
            return 'Opera';
        } elseif (stripos($userAgent, 'Edge') !== false || stripos($userAgent, 'Edg') !== false) {
            return 'Edge';
        } elseif (stripos($userAgent, 'MSIE') !== false || stripos($userAgent, 'Trident') !== false) {
            return 'Internet Explorer';
        } else {
            return 'Unknown';
        }
    }

    /**
     * Detect platform (OS) from User-Agent
     */
    private static function getPlatform(string $userAgent): string
    {
        if (preg_match('/windows|win32/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            return 'Mac';
        } elseif (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return 'iOS';
        } elseif (preg_match('/android/i', $userAgent)) {
            return 'Android';
        } else {
            return 'Unknown';
        }
    }

    /**
     * Detect device type (desktop, mobile, tablet)
     */
    private static function getDeviceType(string $userAgent): string
    {
        if (preg_match('/tablet|ipad|playbook|silk|(puffin)(?!.*(IP|AP|WP))/i', $userAgent)) {
            return 'tablet';
        } elseif (preg_match('/android|webos|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }

    /**
     * Get IP address from request
     */
    public static function getClientIp(): string
    {
        foreach (
            [
                'HTTP_CF_CONNECTING_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR',
            ] as $key
        ) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ipAddress) {
                    $ipAddress = trim($ipAddress);
                    if (
                        filter_var(
                            $ipAddress,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                        ) !== false
                    ) {
                        return $ipAddress;
                    }
                }
            }
        }

        return '0.0.0.0';
    }
}
