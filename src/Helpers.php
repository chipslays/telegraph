<?php 

use Chipslays\Telegraph\Telegraph;

if (! function_exists('image_upload')) {
    /**
     * Upload images to Telegra.ph
     * 
     * @param string|array $image Path to image file
     * @return array Array with permalinks for uplaoded images
     */
    function image_upload($images) {
        return Telegraph::upload($images);
    }
}