<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\ArticlesCollection;

interface ArticleRepository
{
    public function all(): ?ArticlesCollection;
}