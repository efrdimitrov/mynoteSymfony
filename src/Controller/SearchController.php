<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\Client\ClientServiceInterface;
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
     * ClientServiceInterface constructor.
     * @param ClientServiceInterface $clientService
     * @param EventService $eventService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EventService $eventService,
                                ClientServiceInterface $clientService,
                                EntityManagerInterface $entityManager)
    {
        $this->clientService = $clientService;
        $this->eventService = $eventService;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/search_clients", name="search_clients")
     * @param Request $request
     * @return Response
     */
    public function searchClient(Request $request){

        $searchClients = $request->query->get('search_clients');
        $clients = $this->entityManager->getRepository(Client::class)->findByClients($searchClients);

        $viewEvents = $this->eventService->viewEvents();
        $telephone = $this->eventService->telephone();

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

        $viewEvents = $this->eventService->viewEvents();
        $telephone = $this->eventService->telephone();

        return $this->render('clients/clients.html.twig', [
            'clients' => $clients,
            'val' => $searchAddress,
            'view_events' => $viewEvents,
            'telephone' => $telephone,

        ]);
    }




}
