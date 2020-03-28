<?php

namespace App\Controller;

use App\Service\Category\CategoryService;
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
     * EventController constructor.
     * @param EventServiceInterface $eventService
     * @param EntityManagerInterface $entityManager
     * @param CategoryService $categoryService
     */
    public function __construct(EventServiceInterface $eventService,
                                EntityManagerInterface $entityManager,
                                CategoryService $categoryService)
    {
        $this->eventService = $eventService;
        $this->entityManager = $entityManager;
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->redirectToRoute("events");
    }

    /**
     * @Route("/create_event", name="create_event")
     *
     * @param Request $request
     * @return Response
     */

    public function create_event(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $this->eventService->save($event);
        $events = $this->eventService->getAll();
        $viewEvents = $this->eventService->viewEvents();
        $telephone = $this->eventService->telephone();

        return $this->render('events/added_event.html.twig',
            [
                'event' => $this->eventService->getLast(),
                'events' => $events,
                'view_events' => $viewEvents,
                'telephone' => $telephone,
            ]);
    }

    /**
     * @Route("/events", name="events")
     *
     * @return Response
     */
    public function events()
    {
        $telephone = $this->eventService->telephone();
        $getIdPhone = $telephone[0]->getId();

        if (date('j') >= 22 or date('j') < 8 and $telephone[0]->getCategory() == 'платен') {
            $query = $this->entityManager->createQuery("
            UPDATE App\Entity\Event e 
            SET e.category = 'неплатен' 
            WHERE e.id = :id "
            )->setParameter('id', $getIdPhone);
            $query->getResult();
        }

        if (isset($_POST['paymentPhone'])) {
            $query = $this->entityManager->createQuery("
            UPDATE App\Entity\Event e 
            SET e.category = 'платен' 
            WHERE e.id = :id"
            )->setParameter('id', $getIdPhone);
            $query->getResult();
            return $this->redirectToRoute("events");
        }
        $events = $this->eventService->queryEvent();
        $categories = $this->categoryService->getAll();
        $viewEvents = $this->eventService->viewEvents();

        return $this->render("events/events.html.twig",
            [
                'events' => $events,
                'categories' => $categories,
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
        $viewEvents = $this->eventService->viewEvents();
        $telephone = $this->eventService->telephone();
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
     * @Route("/hidden_events", name="hidden_events")
     */
    public function hidden_events()
    {
        $hiddenEvents = $this->eventService->hiddenEvents();
        $categories = $this->categoryService->getAll();
        $viewEvents = $this->eventService->viewEvents();
        $telephone = $this->eventService->telephone();

        return $this->render("events/events.html.twig",
            [
                'events' => $hiddenEvents,
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
        $this->entityManager->createQuery("
            UPDATE App\Entity\Event e 
            SET e.hidden = '1' 
            WHERE e.id = :id")
            ->setParameter('id', $id)
            ->getResult();

        return $this->redirectToRoute("events");
    }
}
