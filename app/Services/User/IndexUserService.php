<?php declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\ResourceNotFoundException;
use App\ApiClient;
use App\Models\ArticlesCollection;
use App\Models\UsersCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class IndexUserService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }
    public function execute(): UsersCollection
    {
        return $this->client->getUserContents();
    }
}