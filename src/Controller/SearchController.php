<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\Client\ClientServiceInterface;
use App\Service\Message\MessageServiceInterface;
use App\Service\Event\EventService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @var ClientServiceInterface
     */
    private $clientService;

    /**
     * @var EventService
     */
    private $eventService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
     
    /**
     * @var MessageServiceInterface
     */
    private $messageService;

    /**
     * ClientServiceInterface constructor.
     * @param ClientServiceInterface $clientService
     * @param EventService $eventService
     * @param EntityManagerInterface $entityManager
     * @param MessageServiceInterface $messageService
     */
    public function __construct(EventService $eventService,
                                ClientServiceInterface $clientService,
                                EntityManagerInterface $entityManager,
                                MessageServiceInterface $messageService)
    {
        $this->clientService = $clientService;
        $this->eventService = $eventService;
        $this->entityManager = $entityManager;
        $this->messageService = $messageService;
    }


    /**
     * @Route("/search_clients", name="search_clients")
     * @param Request $request
     * @return Response
     */
    public function searchClient(Request $request){

        $searchClients = $request->query->get('search_clients');
        $clients = $this->entityManager->getRepository(Client::class)->findByClients($searchClients);

        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render('clients/clients.html.twig', [
            'clients' => $clients,
            'val' => $searchClients,
            'view_events' => $viewEvents,
            'telephone' => $telephone,

        ]);
    }

    /**
     * @Route("/search_address", name="search_address")
     * @param Request $request
     * @return Response
     */
    public function searchAddress(Request $request){

        $searchAddress = $request->query->get('search_address');
        $clients = $this->entityManager->getRepository(Client::class)->findByAddress($searchAddress);

        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render('clients/clients.html.twig', [
            'clients' => $clients,
            'val' => $searchAddress,
            'view_events' => $viewEvents,
            'telephone' => $telephone,

        ]);
    }




}
