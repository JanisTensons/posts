<?php

namespace App\Services\Article\Show;

use App\ApiClient;
use App\Exceptions\ResourceNotFoundException;

class ShowArticleService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->client->getArticleContents()->getCollection()[$request->getArticleId()];

        if ($article == null) {
            throw new ResourceNotFoundException("Article by ID {$request->getArticleId()} not found!");
        }
        return new ShowArticleResponse($article);
    }
}