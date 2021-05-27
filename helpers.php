<?php

use Chipslays\Telegraph\File;

if (! function_exists('upload_files')) {
    /**
     * Upload files to Telegra.ph.
     *
     * @param string|array $files Path to local file.
     * @return array Array with permalinks for uploaded files.
     */
    function upload_files($images) {
        return File::upload($images);
    }
}