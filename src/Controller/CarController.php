<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Service\Car\CarServiceInterface;
use App\Service\Event\EventService;
use App\Service\Message\MessageServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property EntityManagerInterface entityManager
 */
class CarController extends AbstractController
{

    /**
     * @var MessageServiceInterface
     */
    private $messageService;

    /**
     * @var CarServiceInterface
     */
    private $carService;

    /**
     * @var EventService
     */
    private $eventService;

    /**
     * EventController constructor.
     * @param MessageServiceInterface $messageService
     * @param CarServiceInterface $carService
     * @param EntityManagerInterface $entityManager
     * @param EventService $eventService
     */
    public function __construct(MessageServiceInterface $messageService,
                                CarServiceInterface $carService,
                                EntityManagerInterface $entityManager,
                                EventService $eventService)
    {
        $this->messageService = $messageService;
        $this->carService = $carService;
        $this->entityManager = $entityManager;
        $this->eventService = $eventService;
    }

    /**
     * @Route("/cars", name="cars")
     */
    public function cars()
    {
        $cars = $this->carService->allCars();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render('cars/cars.html.twig', [
            'cars' => $cars,
            'view_events' => $viewEvents,
            'telephone' => $telephone,
        ]);
    }

    /**
     * @Route("/create_car", name="create_car")
     * @param Request $request
     * @return Response
     */
    public function createCars(Request $request)
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        $this->carService->save($car);

        $cars = $this->carService->allCars();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render('cars/cars.html.twig',
            [
                'view_events' => $viewEvents,
                'telephone' => $telephone,
                'cars' => $cars,
            ]);
    }

    /**
     * @Route("/edit_car/{id}", name="edit_car")
     *
     * @param Request $request
     * @param Car $car
     * @return Response
     */
    public function editCar(Request $request, Car $car)
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        $cars = $this->carService->allCars();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        $this->entityManager->persist($car);
        $this->entityManager->flush();


        return $this->render('cars/cars.html.twig',
            [
                'form' => $form->createView(),
                'cars' => $cars,
                'view_events' => $viewEvents,
                'telephone' => $telephone,
            ]);
    }

    /**
     * @Route("/delete_car/{id}", name="delete_car")
     *
     * @param Car $car
     * @return Response
     */
    public function deleteCar(Car $car)
    {
        $this->entityManager->remove($car);
        $this->entityManager->flush();

        return $this->redirectToRoute("cars");
    }

    /**
     * @Route("/checked_car/{id}", name="checked_car")
     *
     * @param int $id
     * @return Response
     */
    public function checkedCar(int $id)
    {
        $this->eventService->checkedEventProcess($id);
        return $this->redirectToRoute("cars");
    }
}
