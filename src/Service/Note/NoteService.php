<?php


namespace App\Service\Note;

use App\Entity\Note;
use App\Repository\NoteRepository;

class NoteService implements NoteServiceInterface
{
    /**
     * @return NoteRepository
     */
    private $noteRepository;

    /**
     * NoteService constructor.
     * @param $noteRepository
     */
    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
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

//    /**
//     * @return array
//     */
//    public function mainNotes(): array
//    {
//        $query = $this->entityManager->createQuery(
//            "SELECT n FROM App\Entity\Note n WHERE n.title != 'другите' AND n.title != 'тех-ко'
//            ORDER BY n.change_date DESC"
//        );
//
//        return $query->getResult();
//    }
}