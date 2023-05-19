<?php declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserResponse;
use App\Services\User\Show\ShowUserService;
use App\View;

class UserController
{
    public function index(): ?View
    {
        try {
            $service = new IndexUserService();
            $usersCollection = $service->execute();
            return new View('users', ['users' => $usersCollection->getCollection()]);
        } catch (ResourceNotFoundException $exception) {
            return null;
        }
    }

    public function show(): ?View
    {
        try {
            $userId = $_GET["id"] - 1;
            $service = new ShowUserService();
            $request = $service->execute(new ShowUserRequest($userId));
            $response = new ShowUserResponse($request->getUser(), $request->getArticles());
            return new View('user',
                [
                    'user' => $response->getUser(),
                    'articles' => $response->getArticles()
                ]);
        } catch (ResourceNotFoundException $exception) {
            return null;
        }
    }
}