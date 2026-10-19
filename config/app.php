<?php
/**
 * Application Configuration
 * ក្តឹបកូដ Academy
 */

return [
    'name'           => 'ក្តឹបកូដ Academy',
    'slogan'         => 'រៀនធ្វើ រៀនបង្កើត រៀនដើម្បីជីវិត',
    'version'        => '1.0.0',
    'debug'          => true,
    'timezone'       => 'Asia/Phnom_Penh',
    'currency'       => '$',
    'currency_code'  => 'USD',
    'upload_max_size'=> 20 * 1024 * 1024, // 20 MB
    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'allowed_video_types' => ['video/mp4', 'video/webm', 'video/ogg']
];
