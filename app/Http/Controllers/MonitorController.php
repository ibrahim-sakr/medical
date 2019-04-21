<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

/**
 * Class MonitorController
 * @package App\Http\Controllers
 * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
 */
class MonitorController
{
    /**
     * @return array
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    public function ping(): array
    {
        return [
            'ping' => 'pong',
        ];
    }

    /**
     * @return array
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    public function health(): array
    {
        $isConnected = TRUE;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $isConnected = FALSE;
        }

        return [
            "status" => $isConnected,
            "mysql"  => $isConnected,
        ];
    }
}