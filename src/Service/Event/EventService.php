<?php


namespace App\Service\Event;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class EventService implements EventServiceInterface
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EventService constructor.
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EventRepository $eventRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->eventRepository = $eventRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Event[]
     */
    public function getAll(): array
    {
        return $this->eventRepository->findAll();
    }

    public function get(int $id): Event
    {
        // TODO: Implement get() method.
    }

    public function getLast(): Event
    {
        return $this->eventRepository->findBy([], ['id' => 'DESC'])[0];
    }

    /**
     * @param Event $event
     */
    public function save(Event $event)
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }

    /**
     * @return mixed
     */
    public function queryEvent()
    {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('App\Entity\Event', 'e');
        $rsm->addFieldResult('e', 'id', 'id');
        $rsm->addFieldResult('e', 'name', 'name');
        $rsm->addFieldResult('e', 'date', 'date');
        $rsm->addFieldResult('e', 'category', 'category');
        $rsm->addFieldResult('e', 'days_remaining', 'days_remaining');
        $rsm->addFieldResult('e', 'hidden', 'hidden');

        $query = $this->entityManager->createNativeQuery("
        SELECT *
        FROM events
        WHERE hidden != '1' and date <= ( CURRENT_DATE() + INTERVAL + 364 DAY )
        ORDER BY 
        DAYOFYEAR(DATE_ADD(date, INTERVAL (YEAR(NOW()) - YEAR(date)) YEAR)) < DAYOFYEAR(CURRENT_DATE()),
        DAYOFYEAR(DATE_ADD(date, INTERVAL (YEAR(NOW()) - YEAR(date)) YEAR))
        ", $rsm);

        return $events = $query->getResult();
    }

    /**
     * @return mixed
     */
    public function hiddenEvents()
    {
        return $this->entityManager->createQuery("
            SELECT e FROM App\Entity\Event e
            WHERE e.hidden = '1'
        ")
            ->getResult();
    }
    
    /**
    * @param int $id
    */
    public function hideEventProcess(int $id)
    {
        $this->entityManager->createQuery("
            UPDATE App\Entity\Event e 
            SET e.hidden = '1' 
            WHERE e.id = :id")
            ->setParameter('id', $id)
            ->getResult();
    }

}