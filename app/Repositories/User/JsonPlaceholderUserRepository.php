<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Cache;
use App\Models\Article;
use App\Models\CommentsCollection;
use App\Models\User;
use App\Models\UsersCollection;
use GuzzleHttp\Client;

class JsonPlaceholderUserRepository implements UserRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function all(): ?UsersCollection
    {
        if (!Cache::has('users-all')) {
            $usersUrl = 'https://jsonplaceholder.typicode.com/users';
            $usersJson = $this->client->request('GET', $usersUrl)->getBody()->getContents();
            Cache::remember('users-all', $usersJson);
        } else {
            $usersJson = Cache::get('users-all');
        }
        $usersContents = json_decode($usersJson);

        $articlesUrl = 'https://jsonplaceholder.typicode.com/posts';
        $articlesJson = $this->client->request('GET', $articlesUrl)->getBody()->getContents();
        $articlesContents = json_decode($articlesJson);

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
    }
}