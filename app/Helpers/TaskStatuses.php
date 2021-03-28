<?php

namespace App\Helpers;

class TaskStatuses {
    const COMPLETED = "completed";
    const FAILED = "failed";

    public static function all() {
        return [
            self::COMPLETED,
            self::FAILED,
        ];
    }
}
