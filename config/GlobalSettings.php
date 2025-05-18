<?php

namespace App\Config;

class GlobalSettings
{
    public static function getTimeout(): int
    {
        return 5; // الزمن الأقصى للانتظار بالثواني
    }

    public static function getRetryAttempts(): int
    {
        return 3; // عدد مرات إعادة المحاولة
    }

    public static function getRetryDelay(): int
    {
        return 100; // فترة التأخير بين المحاولات بالميلي ثانية
    }
}