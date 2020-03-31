<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Service\Client\ClientServiceInterface;
use App\Service\Event\EventService;
use App\Service\Message\MessageServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
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
     * @Route("/clients", name="clients")
     */
    public function index()
    {
        $clients = $this->clientService->allClients();
        $viewEvents = $this->eventService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render('clients/clients.html.twig', [
            'clients' => $clients,
            'view_events' => $viewEvents,
            'telephone' => $telephone,
        ]);
    }

    /**
     * @Route("/create_client", name="create_client")
     * @param Request $request
     * @return Response
     */
    public function create_client(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        $this->clientService->save($client);
        $clients = $this->clientService->allClients();
        $viewEvents = $this->eventService->viewEvents();
        $telephone = $this->messageService->telephone();


        return $this->render('clients/clients.html.twig',
            [
                'client' => $this->clientService->save($client),
                'clients' => $clients,
                'view_events' => $viewEvents,
                'telephone' => $telephone,
            ]);
    }

    /**
     * @Route("/edit_client/{id}", name="edit_client")
     *
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function edit(Request $request, Client $client)
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();
            return $this->redirectToRoute("clients");
        }

        return $this->render('clients/clients.html.twig',
            [
                'form' => $form->createView(),
                'client' => $client,
            ]);
    }

}
