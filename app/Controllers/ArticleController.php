<?php declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleResponse;
use App\Services\Article\Show\ShowArticleService;
use App\View;

class ArticleController
{
    private IndexArticleService $indexArticleService;
    private ShowArticleService $showArticleService;

    public function __construct(IndexArticleService $indexArticleService, ShowArticleService $showArticleService)
    {
        $this->indexArticleService = $indexArticleService;
        $this->showArticleService = $showArticleService;
    }

    public function home(): View
    {
        return new View('index', ['articles' => null]);
    }

    public function index(): ?View
    {
        try {
            $service = $this->indexArticleService;
            $articlesCollection = $service->execute();
            return new View('articles', ['articles' => $articlesCollection->getCollection()]);
        } catch (ResourceNotFoundException $exception) {
            return null;
        }
    }

    public function show(): ?View
    {
        try {
            $articleId = $_GET["id"] - 1;
            $service = $this->showArticleService;
            $request = $service->execute(new ShowArticleRequest($articleId));
            $response = new ShowArticleResponse($request->getArticle());
            return new View('article', ['article' => $response->getArticle()]);
        } catch (ResourceNotFoundException $exception) {
            return null;
        }
    }
}