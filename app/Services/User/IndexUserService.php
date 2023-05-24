<?php declare(strict_types=1);

namespace App\Services\User;

use App\Models\UsersCollection;
use App\Repositories\User\UserRepository;

class IndexUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(): UsersCollection
    {
        return $this->userRepository->all();
    }
}