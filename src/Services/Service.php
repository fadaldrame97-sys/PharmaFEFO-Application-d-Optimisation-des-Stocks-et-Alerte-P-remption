<?php

declare(strict_types=1);

class Service
{
    /**
     * Determine the criticality level of a batch based on its expiration date.
     * Returns: CRITICAL (red), WARNING (orange), or OK (green).
     */
    public static function getCriticityLevel(DateTime $expirationDate): string
    {
        $now = new DateTime();
        if ($expirationDate < $now) {
            return 'EXPIRED';
        }

        $daysLeft = (int) $now->diff($expirationDate)->days;

        if ($daysLeft <= 30) {
            return 'CRITICAL';
        }
        if ($daysLeft <= 90) {
            return 'WARNING';
        }
        return 'OK';
    }

    /**
     * Returns a CSS class string for the given criticality level.
     */
    public static function getCriticityClass(string $level): string
    {
        switch ($level) {
            case 'EXPIRED':
            case 'CRITICAL':
                return 'bg-red-500 text-white';
            case 'WARNING':
                return 'bg-yellow-400 text-black';
            case 'OK':
            default:
                return 'bg-green-500 text-white';
        }
    }
}
