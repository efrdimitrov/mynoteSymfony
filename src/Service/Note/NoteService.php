<?php


namespace App\Service\Note;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;

class NoteService implements NoteServiceInterface
{
    /**
     * @return NoteRepository
     */
    private $noteRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * NoteService constructor.
     * @param $noteRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(NoteRepository $noteRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->noteRepository = $noteRepository;
        $this->entityManager = $entityManager;
    }

    public function mainNotes(): array
    {
        return $this->noteRepository->findAll();
    }

    public function othersNotes(): array
    {
        return $this->noteRepository->findBy(array(
        'title' => 'другите'
        ), ['change_date' => 'DESC']);
    }

    public function allMainNotes()
    {
        return $query = $this->entityManager->createQuery(
            "SELECT n FROM App\Entity\Note n WHERE n.title != 'другите' AND n.title != 'тех-ко' 
            ORDER BY n.change_date DESC"
        )
            ->getResult();
    }
}