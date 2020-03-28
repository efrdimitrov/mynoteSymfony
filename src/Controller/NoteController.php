<?php

namespace App\Controller;

use App\Service\Event\EventService;
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
     * NoteController constructor.
     * @param NoteServiceInterface $noteService
     * @param EventService $eventService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(NoteServiceInterface $noteService,
                                EventService $eventService,
                                EntityManagerInterface $entityManager)
    {
        $this->noteService = $noteService;
        $this->eventService = $eventService;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/notes", name="notes")
     *
     * @return Response
     */
    public function notes()
    {
        $query = $this->entityManager->createQuery(
            "SELECT n FROM App\Entity\Note n WHERE n.title != 'другите' AND n.title != 'тех-ко' 
            ORDER BY n.change_date DESC"
        );
        $mainNotes = $query->getResult();

        $events = $this->eventService->queryEvent();
        $viewEvents = $this->eventService->viewEvents();
        $telephone = $this->eventService->telephone();

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
                'note' => $note,
                'notes' => $notes,
                'others_notes' => $this->noteService->othersNotes(),
                'events' => $events,
            ]);
    }
}
