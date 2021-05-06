<?php

$appBaseUrl = env('APP_URL');
$imageBasePath = $appBaseUrl.'/images';

return [
    'app_base_url' => $appBaseUrl,
    'avatar_path' => $imageBasePath.'/avatars/',
    'verification_key' => env('TOKEN_KEY')
];
