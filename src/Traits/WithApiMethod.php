<?php

namespace Telegraph\Traits;

use Telegraph\Exceptions\HttpClientException;
use Telegraph\Exceptions\TelegraphApiException;
use CurlHandle;

trait WithApiMethod
{
    /**
     * @var CurlHandle
     */
    protected CurlHandle $httpClient;

    /**
     * @param CurlHandle $httpClient
     * @return self
     */
    public function setHttpClient(CurlHandle $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @return CurlHandle
     */
    public function getHttpClient(): CurlHandle
    {
        return $this->httpClient;
    }

    /**
     * @return void
     */
    protected function setupDefaultHttpClient(): void
    {
        $httpClient = curl_init();

        curl_setopt($httpClient, CURLOPT_POST, true);
        curl_setopt($httpClient, CURLOPT_TIMEOUT, 30);
        curl_setopt($httpClient, CURLOPT_RETURNTRANSFER, true);

        $this->setHttpClient($httpClient);
    }

    /**
     * @param string $method
     * @param array $params
     * @return array
     *
     * @throws HttpClientException If has CURL errors.
     * @throws TelegraphApiException If Telegraph API returns `ok == false`.
     */
    public function api(string $method, array $params = []): array
    {
        curl_setopt($this->httpClient, CURLOPT_URL, 'https://api.telegra.ph/' . $method);
        curl_setopt($this->httpClient, CURLOPT_POSTFIELDS, $params);

        $result = curl_exec($this->httpClient);

        if (curl_errno($this->httpClient)) {
            throw new HttpClientException(curl_error($this->httpClient));
        }

        $response = json_decode($result, true);

        if ($response['ok'] === false) {
            throw new TelegraphApiException(
                $response['error'] . ', method: ' . $method . ', request params: ' . json_encode($params)
            );
        }

        return $response['result'];
    }
}
