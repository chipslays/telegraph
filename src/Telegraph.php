<?php 

namespace Chipslays\Telegraph;

class Telegraph
{
    const BASE_URL = 'https://telegra.ph';

    /**
     * Upload images to Telegra.ph
     *
     * @param string|array $image Path to image file
     * @return array Array with permalinks for uploaded images
     */
    public static function upload($images) : array
    {
        $premalinks = [];
        
        $curl = curl_init();

        $options = [
            CURLOPT_URL => self::BASE_URL . '/upload/',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 5,
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
       
        foreach ((array) $images as $image) {

            $options[CURLOPT_POSTFIELDS] = [
                'file' => new \CurlFile($image),
            ];

            curl_setopt_array($curl, $options);
    
            $response = json_decode(curl_exec($curl), true);
            $premalinks[] = self::BASE_URL . $response[0]['src'] ?? null;
        }

        curl_close($curl);

        return $premalinks;
    }
}