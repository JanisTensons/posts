<?php declare(strict_types=1);

namespace App\Models;

class Article
{
    private int $id;
    private string $title;
    private string $body;
    private int $userId;
    private User $user;
    private CommentsCollection $comments;

    public function __construct(
        int $id,
        string $title,
        string $body,
        int $userId,
        User $user,
        CommentsCollection $comments
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->userId = $userId;
        $this->user = $user;
        $this->comments = $comments;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getComments(): CommentsCollection
    {
        return $this->comments;
    }
}