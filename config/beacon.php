<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Retention
    |--------------------------------------------------------------------------
    | Number of days to retain heartbeat, execution, and error data.
    */
    'retention_days' => env('BEACON_RETENTION_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Schedule Grace Period
    |--------------------------------------------------------------------------
    | Default grace period in minutes before a scheduled task is considered
    | overdue. Can be overridden per schedule.
    */
    'schedule_grace_period' => env('BEACON_SCHEDULE_GRACE_PERIOD', 5),
];
