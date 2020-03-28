<?php


namespace App\Service\Client;

use App\Entity\Client;

interface ClientServiceInterface
{
    /**
     * @return Client[]
     */
    public function getAll(): array;

    public function save(Client $client);

    public function allClients();
}