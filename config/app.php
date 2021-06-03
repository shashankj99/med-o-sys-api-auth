<?php

$appBaseUrl = env('APP_URL');
$cdnServiceUrl = env('CDN_SERVICE_URL');

return [
    'app_base_url' => $appBaseUrl,
    'upload_avatar_image_url' => $cdnServiceUrl.'/upload/avatar/image',
    'get_avatar_image_url' => $cdnServiceUrl.'/image/avatar/',
    'verification_key' => env('TOKEN_KEY')
];
