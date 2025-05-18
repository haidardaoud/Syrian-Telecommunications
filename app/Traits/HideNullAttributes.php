<?php
namespace App\Traits;

trait HideNullAttributes
{
    public function toArray()
    {
        $array = parent::toArray();

        // التعديل المهم: إزالة الحقول ذات القيمة null فقط
        return array_map(function ($value) {
            if (is_array($value)) {
                return array_filter($value, fn ($item) => !is_null($item));
            }
            return $value;
        }, array_filter($array, fn ($value) => !is_null($value)));
    }
}
