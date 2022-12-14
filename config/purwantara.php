<?php

/**
 * Config for purwantara package.
 *
 * @author ezhasyafaat
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Bearer Token
    |--------------------------------------------------------------------------
    |
    | Basically, Purwantara use OAuth2 for authentication. This value is berare token
    | from purwantara as you request on dashboard purwantara.id
    |
    */
    'token'   => env('PURWANTARA_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Application mode
    |--------------------------------------------------------------------------
    |
    | This value is a mode of your application
    |
    */
    'mode'      => env('PURWANTARA_MODE'),
];
