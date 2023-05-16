<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\View;

class ArticleController
{
    public function getIndex(): View
    {
        return new View('index', ['articles' => null]);
    }

    public function getArticlesContents(): View
    {
        $apiClient = new ApiClient();
        $articleCollection = $apiClient->getArticleContents();
        return new View('articles', ['articles' => $articleCollection->getCollection()]);
    }

    public function getArticleContents(): View
    {
        $apiClient = new ApiClient();
        $articleCollection = $apiClient->getArticleContents();
        return new View('article', ['article' => $articleCollection->getCollection()[$_GET["id"] - 1]]);
    }

    public function getUserContents(): View
    {
        $apiClient = new ApiClient();
        $userCollection = $apiClient->getUserContents();
        return new View('user', ['user' => $userCollection->getCollection()[$_GET["id"] - 1]]);
    }
}