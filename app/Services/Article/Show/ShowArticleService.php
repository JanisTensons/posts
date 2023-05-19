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

        //    if($user == null){
        //        throw new ResourceNotFoundException('User by id '.$request->getUserId().' not found');
        //    }


        return new ShowArticleResponse($article);
    }
}