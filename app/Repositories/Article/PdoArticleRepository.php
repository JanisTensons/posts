<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use App\Models\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use App\Models\ArticlesCollection;

class PdoArticleRepository implements ArticleRepository
{
    private Connection $connection;
    private ArticlesCollection $articlesCollection;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => 'codelex-news',
            'user' => 'root',
            'password' => 'codelex315',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ];
        $this->connection = DriverManager::getConnection($connectionParams);
        $this->articlesCollection = new ArticlesCollection();
    }

    public function all(): ?ArticlesCollection
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $articles = $queryBuilder->select('*')
            ->from('articles')
            ->fetchAllAssociative();

        foreach ($articles as $article) {
            $this->articlesCollection->add(new Article(
                (int)$article['id'],
                $article['title'],
                $article['body'],
                (int)$article['userId'],
                new User(
                    1,
                    'userId-1',
                    'username',
                    'email',
                    []
                ),
                null,
            ));
        }
        return $this->articlesCollection;
    }
}