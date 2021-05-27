<?php

namespace Chipslays\Telegraph;

use GuzzleHttp\Client as GuzzleHttpClient;
use Psr\Http\Message\ResponseInterface;
use Chipslays\Collection\Collection;
use Chipslays\Telegraph\Traits\Methods;
use Chipslays\Telegraph\Exceptions\InvalidContentTypeException;
use Chipslays\Telegraph\Exceptions\RequestException;
use Chipslays\Telegraph\Types\Elements\NodeElement;
use Exception;

class Client
{
    use Methods;

    const BASE_URL = 'https://telegra.ph';

    const API_URL = 'https://api.telegra.ph';

    /**
     * @var GuzzleHttpClient
     */
    private $api;

    public function __construct()
    {
        $this->api = new GuzzleHttpClient(['base_uri' => self::API_URL]);
    }

    /**
     * Remove null values from array.
     *
     * @param array $data
     * @return array
     */
    protected function prepareRequestData(array $data): array
    {
        return array_filter($data, function ($item) {
            return $item !== null;
        });
    }

    /**
     * Handle response from Telegraph.
     *
     * @param ResponseInterface $response
     * @return Collection
     * @throws RequestException
     */
    protected function handleResponse(ResponseInterface $response)
    {
        $response = json_decode($response->getBody()->getContents(), true);

        if ($response['ok'] === false) {
            throw new RequestException($response['error']);
        }

        return collection($response['result']);
    }

    /**
     * @param NodeElement[]|string $content
     * @return array
     * @throws InvalidContentTypeException
     */
    public function decoratePageContent($content): array
    {
        if (is_string($content)) {
            return [$content];
        }

        if (is_array($content)) {
            $result = [];
            foreach ($content as $item) {
                if (!$item instanceof NodeElement) {
                    throw new InvalidContentTypeException();
                }
                $result[] = $item->toArray();
            }

            return $result;
        }

        throw new InvalidContentTypeException();
    }

    /**
     * FIXME: Old part of package, need refactor.
     *
     * Upload files to Telegra.ph.
     *
     * @param string|array $files Path to image file.
     * @return array Array with permalinks for uploaded files.
     */
    public static function uploadImages($files): array
    {
        $links = [];

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

        foreach ((array) $files as $file) {
            $options[CURLOPT_POSTFIELDS] = [
                'file' => new \CurlFile($file),
            ];

            curl_setopt_array($curl, $options);

            $response = json_decode(curl_exec($curl), true);

            if (isset($response['error'])) {
                throw new RequestException($response['error']);
            }

            $links[] = self::BASE_URL . $response[0]['src'] ?? null;
        }

        curl_close($curl);

        return $links;
    }
}