<?php

namespace App\Services\Article\Show;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\Article\ArticleRepository;

class ShowArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->articleRepository->all()->getCollection()[$request->getArticleId()];

        if ($article == null) {
            throw new ResourceNotFoundException("Article by ID {$request->getArticleId()} not found!");
        }
        return new ShowArticleResponse($article);
    }
}