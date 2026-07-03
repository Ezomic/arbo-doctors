<?php

return [

    /*
    |--------------------------------------------------------------------------
    | This app's own slug
    |--------------------------------------------------------------------------
    |
    | Must match the `slug` seeded in Identity's app_definitions table and
    | the `aud` claim Identity issues tokens for when logging into this app.
    |
    */

    'app_slug' => env('SSO_APP_SLUG', 'doctors'),

    /*
    |--------------------------------------------------------------------------
    | Identity service
    |--------------------------------------------------------------------------
    */

    'identity_base_url' => env('IDENTITY_BASE_URL', 'https://identity.test'),

    /*
    |--------------------------------------------------------------------------
    | Public key cache
    |--------------------------------------------------------------------------
    |
    | The RS256 public key is fetched from Identity's .well-known endpoint
    | and cached, so a key rotation on Identity's side doesn't require
    | redeploying every consuming app.
    |
    */

    'public_key_cache_ttl_seconds' => 3600,

];
