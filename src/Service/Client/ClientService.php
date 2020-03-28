<?php

namespace App\Service\Client;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClientService implements ClientServiceInterface
{

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ClientService constructor
     * @param ClientRepository $clientRepository ;
     * @param EntityManagerInterface $entityManager ;
     */
    public function __construct(ClientRepository $clientRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Client[]
     */
    public function getAll(): array
    {
        return $this->clientRepository->findAll();
    }

    public function save(Client $client)
    {
        $this->entityManager->persist($client);
        $this->entityManager->flush();
    }

    public function allClients()
    {
        return $this->entityManager->createQuery("
            SELECT cl FROM App\Entity\Client cl
            ORDER BY cl.id DESC
        ")
            ->setMaxResults(50)
            ->getResult();
    }
}