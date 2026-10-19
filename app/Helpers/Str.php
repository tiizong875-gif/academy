<?php
namespace App\Helpers;

class Str {
    public static function slug(string $title): string {
        // Convert to lowercase
        $slug = mb_strtolower(trim($title), 'UTF-8');
        // Replace non-alphanumeric/non-khmer characters with hyphen
        $slug = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $slug);
        return trim($slug, '-');
    }

    public static function limit(string $text, int $limit = 100, string $end = '...'): string {
        $clean = strip_tags($text);
        if (mb_strlen($clean, 'UTF-8') <= $limit) {
            return $clean;
        }
        return mb_substr($clean, 0, $limit, 'UTF-8') . $end;
    }

    public static function money(float $amount, string $symbol = '$'): string {
        if ($amount <= 0) {
            return 'ឥតគិតថ្លៃ (Free)';
        }
        return $symbol . number_format($amount, 2);
    }

    public static function duration(int $seconds): string {
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        
        if ($minutes < 60) {
            return sprintf("%02d:%02d", $minutes, $remainingSeconds);
        }

        $hours = floor($minutes / 60);
        $remMin = $minutes % 60;
        return sprintf("%02d:%02d:%02d", $hours, $remMin, $remainingSeconds);
    }

    public static function durationHours(int $minutes): string {
        $hours = floor($minutes / 60);
        $remMin = $minutes % 60;
        if ($hours > 0 && $remMin > 0) {
            return "{$hours} ម៉ោង {$remMin} នាទី";
        } elseif ($hours > 0) {
            return "{$hours} ម៉ោង";
        }
        return "{$remMin} នាទី";
    }
}
