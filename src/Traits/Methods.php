<?php

namespace Chipslays\Telegraph\Traits;

use Chipslays\Telegraph\Types\Page;
use Chipslays\Telegraph\Types\Account;
use Chipslays\Telegraph\Types\PageList;

trait Methods
{
    /**
     * @param string $shortName
     * @param string|null $authorName
     * @param string|null $authorUrl
     * @return Account
     */
    public function createAccount(string $shortName, ?string $authorName = null, ?string $authorUrl = null): Account
    {
        $response = $this->api->post('/createAccount', [
            'json' => $this->prepareRequestData([
                'short_name' => $shortName,
                'author_name' => $authorName,
                'author_url' => $authorUrl,
            ]),
        ]);

        $data = $this->handleResponse($response);

        return new Account($data);
    }

    /**
     * @param string $accessToken
     * @param string $shortName
     * @param string|null $authorName
     * @param string|null $authorUrl
     * @return Account
     */
    public function editAccountInfo(string $accessToken, string $shortName, ?string $authorName = null, ?string $authorUrl = null): Account
    {
        $response = $this->api->post('/editAccountInfo', [
            'json' => $this->prepareRequestData([
                'access_token' => $accessToken,
                'short_name' => $shortName,
                'author_name' => $authorName,
                'author_url' => $authorUrl,
            ]),
        ]);

        $data = $this->handleResponse($response);

        return new Account($data);
    }

    /**
     * @param string $accessToken
     * @param array $fields
     * @return Account
     */
    public function getAccountInfo(string $accessToken, array $fields = ['short_name', 'author_name', 'author_url', 'auth_url', 'page_count']): Account
    {
        $response = $this->api->post('/getAccountInfo', [
            'json' => [
                'access_token' => $accessToken,
                'fields' => $fields,
            ],
        ]);

        $data = $this->handleResponse($response);

        return new Account($data);
    }

    /**
     * @param string $accessToken
     * @return Account
     */
    public function revokeAccessToken(string $accessToken): Account
    {
        $response = $this->api->post('/revokeAccessToken', [
            'json' => [
                'access_token' => $accessToken,
            ],
        ]);

        $data = $this->handleResponse($response);

        return new Account($data);
    }

    /**
     * @param string $accessToken
     * @param string $title
     * @param string|NodeElement[] $content
     * @param string|null $authorName
     * @param string|null $authorUrl
     * @param bool $returnContent
     * @return Page
     */
    public function createPage(string $accessToken, string $title, $content, ?string $authorName = null, ?string $authorUrl = null, bool $returnContent = false): Page
    {
        $response = $this->api->post('/createPage', [
            'json' => $this->prepareRequestData([
                'access_token' => $accessToken,
                'title' => $title,
                'content' => $this->decoratePageContent($content),
                'author_name' => $authorName,
                'author_url' => $authorUrl,
                'return_content' => $returnContent,
            ]),
        ]);

        $data = $this->handleResponse($response);

        return new Page($data);
    }

    /**
     * @param string $accessToken
     * @param string $path
     * @param string $title
     * @param string|NodeElement[] $content
     * @param string|null $authorName
     * @param string|null $authorUrl
     * @param boolean $returnContent
     * @return Page
     */
    public function editPage(string $accessToken, string $path, string $title, $content, ?string $authorName = null, ?string $authorUrl = null, bool $returnContent = false): Page
    {
        $response = $this->api->post('/editPage/' . $path, [
            'json' => $this->prepareRequestData([
                'access_token' => $accessToken,
                'title' => $title,
                'content' => $this->decoratePageContent($content),
                'author_name' => $authorName,
                'author_url' => $authorUrl,
                'return_content' => $returnContent,
            ]),
        ]);

        $data = $this->handleResponse($response);

        return new Page($data);
    }

    /**
     * @param string $path
     * @param boolean $returnContent
     * @return Page
     */
    public function getPage(string $path, bool $returnContent = false): Page
    {
        $response = $this->api->post('/getPage/' . $path, [
            'json' => [
                'return_content' => $returnContent,
            ],
        ]);

        $data = $this->handleResponse($response);

        return new Page($data);
    }

    /**
     * Use this method to get a list of pages belonging to a Telegraph account.
     * Returns a PageList object, sorted by most recently created pages first.
     *
     * @param string $accessToken Required. Access token of the Telegraph account.
     * @param integer $offset Sequential number of the first page to be returned.
     * @param integer $limit Limits the number of pages to be retrieved. (0-200)
     * @return PageList
     */
    public function getPageList(string $accessToken, int $offset = 0, int $limit = 50): PageList
    {
        $response = $this->api->post('/getPageList', [
            'json' => [
                'access_token' => $accessToken,
                'offset' => $offset,
                'limit' => $limit,
            ],
        ]);

        $data = $this->handleResponse($response);

        return new PageList($data);
    }

    /**
     * Use this method to get the number of views for a Telegraph article.
     * Returns a PageViews object on success.
     * By default, the total number of page views will be returned.
     *
     * @param string $path Required. Path to the Telegraph page (in the format Title-12-31, where 12 is the month and 31 the day the article was first published).
     * @param integer $year Required if month is passed. If passed, the number of page views for the requested year will be returned.
     * @param integer $month Required if day is passed. If passed, the number of page views for the requested month will be returned.
     * @param integer $day Required if hour is passed. If passed, the number of page views for the requested day will be returned.
     * @param integer $hour If passed, the number of page views for the requested hour will be returned.
     * @return integer
     */
    public function getViews(string $path, int $year = null, int $month = null, int $day = null, int $hour = null): int
    {
        $response = $this->api->post('/getPage', [
            'json' => $this->prepareRequestData([
                'path' => $path,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $hour,
            ]),
        ]);

        $data = $this->handleResponse($response);

        return $data->get('views');
    }
}
