<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
            'permissions' => 0660,
        ],

        'pay' => [
            'driver' => 'daily',
            'path' => storage_path('logs/payments/pay.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],
        
        'start-charging' => [
            'driver' => 'daily',
            'path' => storage_path('logs/charging/starts.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],

        'pre-charged' => [
            'driver' => 'daily',
            'path' => storage_path('logs/charging/pre-charged.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],

        'payment-result' => [
            'driver' => 'daily',
            'path' => storage_path('logs/payments/georgian-card-results.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],
        
        'payment-responses' => [
            'driver' => 'daily',
            'path' => storage_path('logs/payments/georgian-card-responses.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],

        'firebase-update' => [
            'driver' => 'daily',
            'path' => storage_path('logs/firebase/update.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],
        
        'firebase-finish' => [
            'driver' => 'daily',
            'path' => storage_path('logs/firebase/finish.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],
       
        'firebase-payment-failed' => [
            'driver' => 'daily',
            'path' => storage_path('logs/firebase/payment-failed.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],

        'orders-check' => [
            'driver' => 'daily',
            'path' => storage_path('logs/orders-check/orders-check.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],
       
        'feedback-update' => [
            'driver' => 'daily',
            'path' => storage_path('logs/feedback/update.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],

        'feedback-finish' => [
            'driver' => 'daily',
            'path' => storage_path('logs/feedback/finish.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],

        'request-charger' => [
            'driver' => 'daily',
            'path' => storage_path('logs/request/charger.log'),
            'level' => 'debug',
            'permissions' => 0660,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],
    ],

];
