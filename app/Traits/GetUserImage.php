<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait GetUserImage
{

    public function getImageName($image, $mobile)
    {
        // explode the base64 encoded string
        $imgString = explode(",", $image);

        // check if request has image
        if ($image) {
            // get image upload url
            $url = config('app.upload_avatar_image_url');

            // make HTTP post request url to the cdn service
            Http::post($url, [
                'img_string' => $imgString[1],
                'mobile_number' => $mobile
            ])->throw();

            return "{$mobile}.jpg";

        } else {
            // set image name as default
            return 'default.png';
        }
    }
}
