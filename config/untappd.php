<?php

return [
    'access_token' => env('UNTAPPD_ACCESS_TOKEN', ''),
    'user_agent' => 'beers for festival (' . env('UNTAPPD_CLIENT_ID', '') . ')',
    'update_limit' => env('UNTAPPD_UPDATE_LIMIT', 30),
];
