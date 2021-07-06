<?php

namespace Chipslays\Telegraph;

use Chipslays\Telegraph\Exceptions\RequestException;

class File
{
    const BASE_URL = 'https://telegra.ph';

    /**
     * FIXME: Old part of package, code refactoring is needed.
     *
     * Upload files to Telegra.ph.
     *
     * @param string|array $files Path to local file.
     * @return array|bool Array with permalinks for uploaded files, bool False on error.
     */
    public static function upload($files)
    {
        $links = [];

        $curl = curl_init();

        $options = [
            CURLOPT_URL => self::BASE_URL . '/upload/',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => [
                'Content-Type: multipart/form-data',
                'Accept: application/json, text/javascript, */*; q=0.01',
                'X-Requested-With' => 'XMLHttpRequest',
                'Referer' => self::BASE_URL,
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
            ],
        ];

        foreach ((array) $files as $file) {
            $options[CURLOPT_POSTFIELDS] = [
                'file' => new \CurlFile($file),
            ];

            @curl_setopt_array($curl, $options);

            if (!$result = @curl_exec($curl)) {
                return false;
            }

            $response = json_decode($result, true);

            if (isset($response['error'])) {
                throw new RequestException($response['error']);
            }

            $links[] = self::BASE_URL . $response[0]['src'] ?? null;
        }

        curl_close($curl);

        return $links;
    }
}
