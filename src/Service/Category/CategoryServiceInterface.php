<?php


namespace App\Service\Category;


use App\Entity\Category;

interface CategoryServiceInterface
{
    /**
     * @return Category[]
     */
    public function getAll(): array;
}