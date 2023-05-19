<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Exceptions\ResourceNotFoundException;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleResponse;
use App\Services\Article\Show\ShowArticleService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserResponse;
use App\Services\User\Show\ShowUserService;
use App\View;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Translation\Exception\RuntimeException;

class ArticleController
{
    public function home(): View
    {
        return new View('index', ['articles' => null]);
    }

    public function index(): ?View
    {
        try {
            //       $apiClient = new ApiClient();
            //       $articlesCollection = $apiClient->getArticleContents();
            $service = new IndexArticleService();
            $articlesCollection = $service->execute();
            return new View('articles', ['articles' => $articlesCollection->getCollection()]);
        } catch (ResourceNotFoundException $exception) {
            return null;
        }
    }

    public function show(): ?View
    {
        try {
            //      $apiClient = new ApiClient();
            //      $articlesCollection = $apiClient->getArticleContents();
            $articleId = $_GET["id"] - 1;
            $service = new ShowArticleService();
            $request = $service->execute(new ShowArticleRequest($articleId));
            $response = new ShowArticleResponse($request->getArticle());
            return new View('article', ['article' => $response->getArticle()]);
        } catch (ResourceNotFoundException $exception) {
            return null;
        }
    }
}