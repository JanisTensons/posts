<?php declare(strict_types=1);

namespace App\Models;

class ArticlesCollection
{
    private array $collection = [];

    public function add(Article $article): void
    {
        $this->collection[] = $article;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }
}