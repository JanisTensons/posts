<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Cache;
use App\Models\Article;
use App\Models\ArticlesCollection;
use App\Models\Comment;
use App\Models\CommentsCollection;
use App\Models\User;
use GuzzleHttp\Client;

class JsonPlaceholderArticleRepository implements ArticleRepository
{
    private Client $client;
    private ArticlesCollection $articlesCollection;

    public function __construct()
    {
        $this->client = new Client();
        $this->articlesCollection = new ArticlesCollection();
    }

    public function all(): ?ArticlesCollection
    {
        if (!Cache::has('articles-all')) {
            $articlesUrl = 'https://jsonplaceholder.typicode.com/posts';
            $articlesJson = $this->client->request('GET', $articlesUrl)->getBody()->getContents();
            Cache::remember('articles-all', $articlesJson);
        } else {
            $articlesJson = Cache::get('articles-all');
        }
        $articlesContents = json_decode($articlesJson);

        foreach ($articlesContents as $article) {
            $userId = $article->userId;
            $userUrl = "https://jsonplaceholder.typicode.com/users/$userId";
            $userCacheKey = 'user-' . $userId;

            if (!Cache::has($userCacheKey)) {
                $userJson = $this->client->request('GET', $userUrl)->getBody()->getContents();
                Cache::remember($userCacheKey, $userJson);
            } else {
                $userJson = Cache::get($userCacheKey);
            }
            $userContents = json_decode($userJson);

            $articleId = $article->id;
            $commentsUrl = "https://jsonplaceholder.typicode.com/comments?postId=$articleId";
            $commentsCacheKey = 'comments-' . $articleId;

            if (!Cache::has($commentsCacheKey)) {
                $commentsJson = $this->client->request('GET', $commentsUrl)->getBody()->getContents();
                Cache::remember($commentsCacheKey, $commentsJson);
            } else {
                $commentsJson = Cache::get($commentsCacheKey);
            }
            $commentsContents = json_decode($commentsJson);

            $commentsCollection = new CommentsCollection();
            $userArticles = [];

            foreach ($commentsContents as $comment) {
                $commentsCollection->add(new Comment(
                    $comment->postId,
                    $comment->id,
                    $comment->name,
                    $comment->email,
                    $comment->body
                ));
            }
            $this->articlesCollection->add(new Article(
                $article->id,
                $article->title,
                $article->body,
                $article->userId,
                new User(
                    $userContents->id,
                    $userContents->name,
                    $userContents->username,
                    $userContents->email,
                    $userArticles
                ),
                $commentsCollection
            ));
        }
        return $this->articlesCollection;
    }
}