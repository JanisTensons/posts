<?php declare(strict_types=1);

namespace App\Services\Article;

use App\ApiClient;
use App\Models\ArticlesCollection;

class IndexArticleService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function execute(): ArticlesCollection
    {
        return $this->client->getArticleContents();
    }
}