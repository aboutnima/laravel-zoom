<?php

return [
    'base_url' => env('ZOOM_BASE_URL', 'https://zoom.us/oauth/authorize'),
    'client_id' => env('ZOOM_CLIENT_ID', ''),
    'client_secret' => env('ZOOM_CLIENT_SECRET', ''),
    'redirect_url' => env('ZOOM_REDIRECT_URL', ''),
];
