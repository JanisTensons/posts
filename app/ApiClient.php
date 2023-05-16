<?php declare(strict_types=1);

namespace App;

use App\Models\Article;
use App\Models\Comment;
use App\Models\CommentsCollection;
use App\Models\User;
use App\Models\ArticlesCollection;
use App\Models\UsersCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private Client $client;
    private ArticlesCollection $articlesCollection;

    public function __construct()
    {
        $this->client = new Client();
        $this->articlesCollection = new ArticlesCollection();
    }

    public function getArticleContents(): ?ArticlesCollection
    {
        try {
            if (!Cache::has('articles-all')) {
                $url = 'https://jsonplaceholder.typicode.com/posts';
                $response = $this->client->request('GET', $url);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('articles-all', $responseJson);
            } else {
                $responseJson = Cache::get('articles-all');
            }
            $articlesContents = json_decode($responseJson);

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
                $user = json_decode($userJson);

                $articleId = $article->id;
                $commentsUrl = "https://jsonplaceholder.typicode.com/comments?postId=$articleId";
                $commentsCacheKey = 'comments-' . $articleId;

                if (!Cache::has($commentsCacheKey)) {
                    $commentsJson = $this->client->request('GET', $commentsUrl)->getBody()->getContents();
                    Cache::remember($commentsCacheKey, $commentsJson);
                } else {
                    $commentsJson = Cache::get($commentsCacheKey);
                }
                $comments = json_decode($commentsJson);

                $commentsCollection = new CommentsCollection();
                $userArticles = [];

                foreach ($comments as $comment) {
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
                    new User($user->id, $user->name, $user->username, $user->email, $userArticles),
                    $commentsCollection
                ));
            }
            return $this->articlesCollection;

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getUserContents(): ?UsersCollection
    {
        try {
            if (!Cache::has('users-all')) {
                $url = 'https://jsonplaceholder.typicode.com/users';
                $response = $this->client->request('GET', $url);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('users-all', $responseJson);
            } else {
                $responseJson = Cache::get('users-all');
            }
            $usersContents = json_decode($responseJson);

            $articlesUrl = 'https://jsonplaceholder.typicode.com/posts';
            $articlesResponse = $this->client->request('GET', $articlesUrl);
            $articlesResponseJson = $articlesResponse->getBody()->getContents();
            $articlesContents = json_decode($articlesResponseJson);

            $usersCollection = new UsersCollection();
            $commentsCollection = new CommentsCollection();

            foreach ($usersContents as $user) {
                $userId = $user->id;
                $userArticles = [];

                foreach ($articlesContents as $article) {
                    if ($article->userId === $userId) {
                        $userArticles[] = new Article(
                            $article->id,
                            $article->title,
                            $article->body,
                            $article->userId,
                            new User($user->id, $user->name, $user->username, $user->email, $userArticles),
                            $commentsCollection
                        );
                    }
                }
                $usersCollection->add(new User($user->id, $user->name, $user->username, $user->email, $userArticles
                ));
            }
            return $usersCollection;

        } catch (GuzzleException $exception) {
            return null;
        }
    }
}