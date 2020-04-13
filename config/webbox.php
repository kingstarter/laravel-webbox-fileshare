<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Webbox config file
    |--------------------------------------------------------------------------
    |
    | Contains application specific configurations
    |
    */

    // The general authentication pin
    'authpin' => env('APP_AUTH_PIN', 'passw0rd!'),

    // Maximum allowed file size in megabytes (should fit with php & webserver upload limits)
    'max_filesize' => env('MAX_FILESIZE_MB', '256'),

    /*
    |--------------------------------------------------------------------------
    | Honeypot configuration
    |--------------------------------------------------------------------------
    |
    | Configure honeypot security
    |
    */

    // Add honeypot field in general
    'honeypot_enabled' => env('HONEYPOT_ENABLED', true),

    // Honeypot field name for form input, should be a known field with some random chars
    'honeypot_field' => env('HONEYPOT_FIELD', 'phone_number_4f3dx'),
];
