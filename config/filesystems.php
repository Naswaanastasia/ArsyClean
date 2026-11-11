<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'supabase' => [
            'driver'                  => 's3',
            'key'                     => env('SUPABASE_ACCESS_KEY_ID'),
            'secret'                  => env('SUPABASE_SECRET_ACCESS_KEY'),
            'region'                  => env('SUPABASE_DEFAULT_REGION', 'eu-west-1'),
            'bucket'                  => env('SUPABASE_BUCKET', 'bukti-pembayaran'),
            'endpoint'                => env('SUPABASE_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'bucket_endpoint'         => false,
            'throw'                   => true,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];