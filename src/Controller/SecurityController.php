<?php

namespace App\Controller;

use App\Service\Event\EventService;
use App\Service\Note\NoteServiceInterface;
use App\Service\Message\MessageServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * @var NoteServiceInterface
     */
    private $noteService;

    /**
     * @var EventService
     */
    private $eventService;
    
    /**
     * @var MessageServiceInterface
     */
    private $messageService;

    /**
     * NoteController constructor.
     * @param NoteServiceInterface $noteService
     * @param EventService $eventService
     * @param MessageServiceInterface $messageService
     */
    public function __construct(NoteServiceInterface $noteService,
                                EventService $eventService,
                                MessageServiceInterface $messageService)
    {
        $this->noteService = $noteService;
        $this->eventService = $eventService;
        $this->messageService = $messageService;
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render('security/login.html.twig', [
            'error'         => $error,
            'last_username' => $lastUsername,
            'view_events'    => $viewEvents,
            'telephone' => $telephone,
        ]);
    }
}
