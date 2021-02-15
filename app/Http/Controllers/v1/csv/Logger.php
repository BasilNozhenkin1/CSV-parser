<?php

namespace App\Http\Controllers\v1\csv;

use App\Models\Log;
use App\Http\Patterns\Singleton;

class Logger extends Singleton
{
    public function writeLog(string $message): void
    {
        Log::create(['message'=> $message]);
    }

    public static function log(string $message): void
    {
        $logger = static::getInstance();
        $logger->writeLog($message);
    }
}
