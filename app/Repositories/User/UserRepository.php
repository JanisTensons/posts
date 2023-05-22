<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\UsersCollection;

interface UserRepository
{
    public function all(): ?UsersCollection;
}