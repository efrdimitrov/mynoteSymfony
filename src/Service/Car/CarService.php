<?php


namespace App\Service\Car;

use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarService implements CarServiceInterface
{
    /**
     * @var CarRepository
     */
    private $carRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EventService constructor.
     * @param CarRepository $carRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CarRepository $carRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->carRepository = $carRepository;
        $this->entityManager = $entityManager;
    }

    public function save(Car $car)
    {
        $this->entityManager->persist($car);
        $this->entityManager->flush();
    }

    public function allCars()
    {
        return $this->entityManager->createQuery("
            SELECT cr FROM App\Entity\Car cr
            ORDER BY cr.id DESC
        ")
            ->getResult();
    }

}