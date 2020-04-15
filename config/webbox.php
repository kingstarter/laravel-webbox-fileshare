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

    // Footer text, can also be empty
    'footer_text' => env('FOOTER_TEXT', '&#169; 2020 powered by KingStarter GbR'),
    'footer_link' => env('FOOTER_LINK', 'https://kingstarter.de'),

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

    /*
    |--------------------------------------------------------------------------
    | Storage lifetime
    |--------------------------------------------------------------------------
    |
    | Set an array of possible storage lifetime durations when generating
    | data links. The labels for generic time periods are translated
    | automatically with singular and plural modifications added.
    |
    | NOTE: Cleanup schedules are set to everyFifteenMinutes, to provide
    |       even shorter lifetimes, the app/Console/Kernel.php cleanup
    |       schedules need to be called more frequently.
    |
    */

    // Value format: /[0-9]+ [a-z]+/
    'storage_lifetime' => [
        '1 hour',
        '6 hours',
        '1 day',
        '2 days',
        '3 days',
        '1 week',
        '2 weeks',
        '1 month',
        '2 months',
        '3 months',
        '6 months',
        '1 year',
    ],

    // The default lifetime should be one option within the storage duration array
    'default_lifetime' => '1 month',
];
