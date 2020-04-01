<?php

namespace App\Controller;

use App\Service\Event\EventService;
use App\Service\Message\MessageServiceInterface;
use App\Service\Note\NoteServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Note;
use App\Form\NoteType;
use Symfony\Component\HttpFoundation\Request;


class NoteController extends AbstractController
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
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageServiceInterface
     */
    private $messageService;

    /**
     * NoteController constructor.
     * @param NoteServiceInterface $noteService
     * @param EventService $eventService
     * @param EntityManagerInterface $entityManager
     * @param MessageServiceInterface $messageService
     */
    public function __construct(NoteServiceInterface $noteService,
                                EventService $eventService,
                                EntityManagerInterface $entityManager,
                                MessageServiceInterface $messageService)
    {
        $this->noteService = $noteService;
        $this->eventService = $eventService;
        $this->entityManager = $entityManager;
        $this->messageService = $messageService;
    }


    /**
     * @Route("/notes", name="notes")
     *
     * @return Response
     */
    public function notes()
    {
        $mainNotes = $this->noteService->allMainNotes();
        $events = $this->eventService->queryEvent();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render("notes/notes.html.twig",
            [
                'notes' => $mainNotes,
                'others_notes' => $this->noteService->othersNotes(),
                'events' => $events,
                'view_events' => $viewEvents,
                'telephone' => $telephone,
            ]);
    }

    /**
     * @Route("/edit_note/{id}", name="edit_note")
     *
     * @param Request $request
     * @param Note $note
     * @return Response
     */
    public function edit(Request $request, Note $note)
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->entityManager->persist($note);
            $this->entityManager->flush();

            return $this->redirectToRoute("notes");
        }

        $notes = $this->noteService->mainNotes();
        $events = $this->eventService->queryEvent();

        return $this->render("notes/notes.html.twig",
            [
                'form' => $form->createView(),
                'notes' => $notes,
                'others_notes' => $this->noteService->othersNotes(),
                'events' => $events,
            ]);
    }
}
