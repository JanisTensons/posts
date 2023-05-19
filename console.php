<?php

use App\Models\Article;
use App\Models\User;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

require_once 'vendor/autoload.php';

[, $resource, $id] = $argv;

$resource = $argv[1] ?? null;
$id = $argv[2] ?? null;

switch ($resource) {
    case 'articles':
        if ($id != null) {
            echo "Invalid resource" . PHP_EOL;
        } else {
            $service = new IndexArticleService();
            $articles = $service->execute();

            echo "\n---------ARTICLES----------\n";
            foreach ($articles as $article) {
                /** @var Article $article */
                echo " Author: {$article->getUserId()}" . PHP_EOL;
                echo " Title: {$article->getTitle()}" . PHP_EOL;
                echo " Body: {$article->getBody()}" . PHP_EOL;
                echo "---------------------------\n\n";
            }
        }
        break;

    case 'article':
        if ($id != null) {
            $service = new ShowArticleService();
            $request = new ShowArticleRequest($id);
            $article = $service->execute($request);

            echo "\n----------ARTICLE----------\n";
            echo " Author: {$article->getArticleUserId()}" . PHP_EOL;
            echo " Title: {$article->getArticle()->getTitle()}" . PHP_EOL;
            echo " Body: {$article->getArticle()->getBody()}" . PHP_EOL;
            echo "---------------------------\n\n";

        } else {
            echo "Invalid article ID." . PHP_EOL;
        }
        break;

    case 'article-author':
        if ($id != null) {
            $articleService = new ShowArticleService();
            $articleRequest = new ShowArticleRequest($id);
            $article = $articleService->execute($articleRequest);

            if ($article != null) {
                $userId = $article->getArticleUserId();
                $userService = new ShowUserService();
                $userRequest = new ShowUserRequest($userId);
                $user = $userService->execute($userRequest);

                if ($user != null) {

                    echo "\n----------USER----------\n";
                    echo " Name: {$user->getUser()->getName()}" . PHP_EOL;
                    echo " username: {$user->getUser()->getUsername()}" . PHP_EOL;
                    echo " email: {$user->getUser()->getEmail()}" . PHP_EOL;
                    echo "--------------------------\n\n";

                } else {
                    echo "User not found." . PHP_EOL;
                }
            } else {
                echo "Article not found." . PHP_EOL;
            }
        } else {
            echo "Invalid article ID." . PHP_EOL;
        }
        break;

    case 'users':
        if ($id != null) {
            echo "Invalid resource" . PHP_EOL;
        } else {
            $service = new IndexUserService();
            $users = $service->execute();

            echo "\n---------USERS----------\n";
            foreach ($users as $user) {
                /** @var User $user */
                echo " Name: {$user->getName()}" . PHP_EOL;
                echo " username: {$user->getUsername()}" . PHP_EOL;
                echo " email: {$user->getEmail()}" . PHP_EOL;
                echo "---------------------------\n\n";
            }
        }
        break;

    case 'user':
        if ($id != null) {
            $service = new ShowUserService();
            $request = new ShowUserRequest($id);
            $user = $service->execute($request);

            echo "\n----------USER----------\n";
            echo " Name: {$user->getUser()->getName()}" . PHP_EOL;
            echo " username: {$user->getUser()->getUsername()}" . PHP_EOL;
            echo " email: {$user->getUser()->getEmail()}" . PHP_EOL;
            echo "--------------------------\n\n";

        } else {
            echo "Invalid user ID." . PHP_EOL;
        }
        break;
    default:
        echo "Invalid resource." . PHP_EOL;
        break;
}