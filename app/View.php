<?php declare(strict_types=1);

namespace App;

class View
{
    private string $template;
    private array $articlesCollection;

    public function __construct(string $template, array $articlesCollection)
    {
        $this->template = $template;
        $this->articlesCollection = $articlesCollection;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getArticlesCollection(): array
    {
        return $this->articlesCollection;
    }
}