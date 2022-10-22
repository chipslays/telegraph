<?php

use Telegraph\File;

require __DIR__ . '/../vendor/autoload.php';

// returns string
$imageUrl = File::upload('/path/to/local/image.jpg');

// can pass url
$imageUrl = File::upload('https://example.com/image.jpg');

// returns array with preserved keys
// ['my_nudes' => 'https://telegra.ph/*, ....]
$imageUrl = File::upload([
    'my_nudes' => '/path/to/local/image.jpg',
    'home_video_with_my_gf' => '/path/to/local/video.mp4',
]);