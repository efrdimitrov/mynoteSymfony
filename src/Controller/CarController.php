<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Service\Car\CarServiceInterface;
use App\Service\Message\MessageServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * EventController constructor.
     * @param MessageServiceInterface $messageService
     * @param CarServiceInterface $carService
     */
    public function __construct(MessageServiceInterface $messageService,
                                CarServiceInterface $carService)
    {
        $this->messageService = $messageService;
        $this->carService = $carService;
    }
//    /**
//     * @Route("/cars", name="cars")
//     */
//    public function index()
//    {
//        return $this->render('cars/cars.html.twig', [
//            'controller_name' => 'CarController',
//        ]);
//    }

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
     * @Route("/cars", name="cars")
     */
    public function cars()
    {
//        $this->carService->changeOfStatus();
        $cars = $this->carService->allCars();
        $viewEvents = $this->messageService->viewEvents();
        $telephone = $this->messageService->telephone();

        return $this->render('cars/cars.html.twig', [
            'cars' => $cars,
            'view_events' => $viewEvents,
            'telephone' => $telephone,
        ]);
    }
}
