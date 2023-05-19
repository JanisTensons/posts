<?php

namespace App\Services\User\Show;

use App\ApiClient;
use App\Exceptions\ResourceNotFoundException;

class ShowUserService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }
    public function execute(ShowUserRequest $request): ShowUserResponse
    {
        $users = $this->client->getUserContents()->getCollection();
        $userId = $request->getUserId();

   //    if (!isset($users[$userId])) {
    //        throw new ResourceNotFoundException('User by ID ' . $userId . ' not found');
    //    }

        $user = $users[$userId];
        $articles = $user->getUserArticles();

        return new ShowUserResponse($user, $articles);
    }
}