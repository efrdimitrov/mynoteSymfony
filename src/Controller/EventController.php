<?php

namespace App\Controller;

use App\Service\Category\CategoryService;
use App\Service\Message\MessageServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;
use App\Entity\Category;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Event\EventServiceInterface;


/**
 * @method createQueryBuilder(string $string)
 */
class EventController extends AbstractController
{

    /**
     * @var EventServiceInterface
     */
    private $eventService;

    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageServiceInterface
     */
    private $messageService;

    /**
     * EventController constructor.
     * @param EventServiceInterface $eventService
     * @param EntityManagerInterface $entityManager
     * @param CategoryService $categoryService
     * @param MessageServiceInterface $messageService
     */
    public function __construct(EventServiceInterface $eventService,
                                EntityManagerInterface $entityManager,
                                CategoryService $categoryService,
                                MessageServiceInterface $messageService)
    {
        $this->eventService = $eventService;
        $this->entityManager = $entityManager;
        $this->categoryService = $categoryService;
        $this->messageService = $messageService;
    }

    /**
     * @Route("/events", name="events")
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function events()
    {

        $this->messageService->newPhoneBill();
        $events = $this->eventService->queryEvent();
        $categories = $this->categoryService->getAll();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render("events/events.html.twig",
            [
                'events' => $events,
                'categories' => $categories,
                'view_events' => $viewEvents,
                'telephone' => $telephone,
            ]);
    }


    /**
     * @Route("/create_event", name="create_event")
     *
     * @param Request $request
     * @return Response
     */
    public function createEvent(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $this->eventService->save($event);
        $events = $this->eventService->getAll();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render('events/added_event.html.twig',
            [
                'event' => $this->eventService->getLast(),
                'events' => $events,
                'view_events' => $viewEvents,
                'telephone' => $telephone,
            ]);
    }

    /**
     * @Route("/edit_event/{id}", name="edit_event")
     *
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function edit(Request $request, Event $event)
    {
        $events = $this->eventService->queryEvent();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            return $this->redirectToRoute("events");
        }
        $categoryRepository = $this
            ->getDoctrine()
            ->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();
        return $this->render('events/events.html.twig',
            [
                'form' => $form->createView(),
                'events' => $events,
                'categories' => $categories,
                'view_events' => $viewEvents,
                'telephone' => $telephone,
            ]);
    }

    /**
     * @Route("/delete_event/{id}", name="delete_event")
     *
     * @param Event $event
     * @return Response
     */
    public function delete(Event $event)
    {
        $this->entityManager->remove($event);
        $this->entityManager->flush();

        return $this->redirectToRoute("events");
    }

    /**
     * @Route("/hidden_events_page", name="hidden_events_page")
     */
    public function hiddenEventsPage()
    {
        $statusEvents = $this->eventService->getHiddenEvents();
        $categories = $this->categoryService->getAll();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render("events/events.html.twig",
            [
                'events' => $statusEvents,
                'categories' => $categories,
                'view_events' => $viewEvents,
                'telephone' => $telephone,
            ]);
    }

    /**
     * @Route("/hide_event/{id}", name="hide_event")
     *
     * @param int $id
     * @return Response
     */
    public function hideEvent(int $id)
    {
        $this->eventService->hideEventProcess($id);
        return $this->redirectToRoute("events");
    }

}

