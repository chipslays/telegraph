<?php

namespace Telegraph;

use Telegraph\Exceptions\FileUploaderException;
use CURLFile;

/**
 * Class for upload files to Telegraph server.
 */
class File
{
    protected const TELEGRAPH_BASE_URL = 'https://telegra.ph';

    /**
     * Upload files to Telegra.ph server.
     *
     * Returns array of permalinks for uploaded files or string for signle file,
     * returns `false` on request error.
     *
     * @param string|array $files Array of local files or signle local file as string.
     * @return string|array|bool
     *
     * @throws FileUploaderException
     */
    public static function upload(string|array $files): string|array|bool
    {
        $httpClient = curl_init();

        $options = [
            CURLOPT_URL => self::TELEGRAPH_BASE_URL . '/upload/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true,
        ];

        $result = [];

        foreach ((array) $files as $key => $file) {

            $options[CURLOPT_POSTFIELDS] = [
                'file' => new CURLFile($file),
            ];

            $options[CURLOPT_HTTPHEADER] = [
                'Content-Type: multipart/form-data',
                'Accept: application/json, text/javascript, */*; q=0.01',
                'X-Requested-With' => 'XMLHttpRequest',
                'Referer' => self::TELEGRAPH_BASE_URL,
                'User-Agent' => self::getRandomUserAgent(),
            ];

            curl_setopt_array($httpClient, $options);

            if (!$response = @curl_exec($httpClient)) {
                return false;
            }

            $response = json_decode($response, true);

            if (isset($response['error'])) {
                throw new FileUploaderException($response['error']);
            }

            $result[$key] = self::TELEGRAPH_BASE_URL . $response[0]['src'] ?? null;
        }

        curl_close($httpClient);

        return count($result) > 1 ? $result : array_values($result)[0];
    }

    /**
     * @return string
     */
    protected static function getRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:105.0) Gecko/20100101 Firefox/105.0',
            'Mozilla/5.0 (X11; Linux x86_64; rv:105.0) Gecko/20100101 Firefox/105.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; rv:105.0) Gecko/20100101 Firefox/105.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:105.0) Gecko/20100101 Firefox/105.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:104.0) Gecko/20100101 Firefox/104.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Safari/605.1.15',
        ];

        return $userAgents[array_rand($userAgents)];
    }
}
