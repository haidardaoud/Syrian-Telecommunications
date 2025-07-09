<?php
namespace App\DTO;

use Carbon\Carbon;

class UsageLogDTO
{
    public static function fromApiResponse(array $item)
    {
        $beginTime = Carbon::createFromFormat('YmdHis', $item['BeginTime']);
        $endTime = Carbon::createFromFormat('YmdHis', $item['EndTime']);

        $uploadMB = round($item['InputBytes'] / (1024 * 1024), 2);
        $downloadMB = round($item['OutputBytes'] / (1024 * 1024), 2);
        $totalMB = round($uploadMB + $downloadMB, 2);

        return [
            'login_time' => $beginTime->format('Y/m/d h:i:s A'),
            'logout_time' => $endTime->format('Y/m/d h:i:s A'),
            'duration' => gmdate('H:i', $item['TimeLength']),
            'upload_MB' => $uploadMB,
            'download_MB' => $downloadMB,
            'total_MB' => $totalMB,
        ];
    }
}
