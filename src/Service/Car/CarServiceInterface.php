<?php


namespace App\Service\Car;


use App\Entity\Car;

interface CarServiceInterface
{
    public function allCars();

    public function save(Car $car);
}