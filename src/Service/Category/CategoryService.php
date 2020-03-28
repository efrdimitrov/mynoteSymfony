<?php


namespace App\Service\Category;

use App\Repository\CategoryRepository;
use App\Entity\Category;

class CategoryService implements CategoryServiceInterface
{

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * EventService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @return Category[]
     */
    public function getAll(): array
    {
        return $this->categoryRepository->findAll();
    }
}