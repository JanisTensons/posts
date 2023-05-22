<?php

namespace App\Services\User\Show;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;

class ShowUserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new JsonPlaceholderUserRepository();
    }

    public function execute(ShowUserRequest $request): ShowUserResponse
    {
        $users = $this->userRepository->all()->getCollection();
        $userId = $request->getUserId();

        if ($users[$userId] == null) {
            throw new ResourceNotFoundException("User by ID $userId not found!");
        }
        $user = $users[$userId];
        $articles = $user->getUserArticles();

        return new ShowUserResponse($user, $articles);
    }
}