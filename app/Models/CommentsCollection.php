<?php declare(strict_types=1);

namespace App\Models;

class CommentsCollection
{
    private array $collection = [];

    public function add(Comment $comment): void
    {
        $this->collection[] = $comment;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }
}