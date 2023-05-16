<?php declare(strict_types=1);

namespace App\Models;

class UsersCollection
{
    private array $collection = [];

    public function add(User $user): void
    {
        $this->collection[] = $user;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }
}