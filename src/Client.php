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
}
