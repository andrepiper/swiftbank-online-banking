<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ID Obfuscation
    |--------------------------------------------------------------------------
    |
    | These settings control how database IDs are obfuscated in URLs.
    |
    */
    'id_obfuscation' => [
        // The salt used for hashing. Change this to a random string in production.
        'salt' => env('ID_OBFUSCATION_SALT', 'SwiftBank'),

        // The minimum length of obfuscated IDs
        'min_length' => 8,
    ],
];
