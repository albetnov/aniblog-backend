<?php

/**
 * Configure your app redirection to your SPA Route Endpoints here.
 */

return [
    'base_url' => env('SPA_URL'), // This line should keep as is. If you want to change it, please change it from ".env" file.
    'login_endpoint' => '/login',
    'reset_password_endpoint' => '/reset-password',
    'authenticated_endpoint' => '/dashboard'
];
