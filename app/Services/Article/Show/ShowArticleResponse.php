<?php

namespace App\Services\Article\Show;

use App\Models\Article;

class ShowArticleResponse
{
    private Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function getArticleUserId(): int
    {
        return $this->article->getUserId();
    }
}