<?php
namespace App\Constants;

class PhoneFormat
{
    /**
     * Format phone number to 62xxxxxxxxxxx
     */
    public static function format(string $value): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $value);

        if (empty($cleaned)) {
            return '';
        }

        if (str_starts_with($cleaned, '62')) {
            return $cleaned;
        }

        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }

        return '62' . $cleaned;
    }

    /**
     * Remove 62 prefix for display
     */
    public static function display(string $value): string
    {
        return str_replace('62', '', $value);
    }

    /**
     * Validation rule
     */
    public const VALIDATION_RULE = 'required|regex:/^62[0-9]{9,13}$/';
}
