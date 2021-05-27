<?php

use Chipslays\Telegraph\File;

require __DIR__ . '/../vendor/autoload.php';

$links = File::upload('video.mp4');
$links = File::upload('nudes.jpg');
$links = File::upload(['video.mp4', 'nudes.jpg']);

// helper function
$links = upload_files('video.mp4');
$links = upload_files(['video.mp4', 'image.png']);

print_r($links);

// Array
// (
//     [0] => https://telegra.ph/file/xxxxxxxxxx.mp4
//     [1] => https://telegra.ph/file/xxxxxxxxxx.png
// )