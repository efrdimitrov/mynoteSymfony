<?php


namespace App\Service\Event;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Exception;

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
        $rsm->addFieldResult('e', 'status', 'status');

        $query = $this->entityManager->createNativeQuery("
        SELECT *
        FROM events
        WHERE status = 0 and date <= ( CURRENT_DATE() + INTERVAL + 364 DAY )
        ORDER BY 
        DAYOFYEAR(DATE_ADD(date, INTERVAL (YEAR(NOW()) - YEAR(date)) YEAR)) < DAYOFYEAR(CURRENT_DATE()),
        DAYOFYEAR(DATE_ADD(date, INTERVAL (YEAR(NOW()) - YEAR(date)) YEAR))
        ", $rsm);

        return $events = $query->getResult();
    }

    /**
     * @return mixed
     */
    public function getHiddenEvents()
    {
        return $this->entityManager->createQuery("
            SELECT e FROM App\Entity\Event e
            WHERE e.status = '2'
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
            SET e.status = '2' 
            WHERE e.id = :id")
            ->setParameter('id', $id)
            ->getResult();
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function checkedEventProcess(int $id)
    {
        $dateNow = new \DateTime('now');
        $dayOfYearNow = $dateNow->format('z');

        $this->entityManager->createQuery("
            UPDATE App\Entity\Event e 
            SET e.status = 1, e.checked = $dayOfYearNow
            WHERE e.id = :id")
            ->setParameter('id', $id)
            ->getResult();
    }

    public function changeOfStatus()
    {
        $status = false;
        $isChangeStatusEvent = false;
        if ($this->changeOfStatusEvent()) {
            $status = $this->changeOfStatusEvent()[0]->getStatus();
            $checked = $this->changeOfStatusEvent()[0]->getChecked();
            $daysRemaining = $this->changeOfStatusEvent()[0]->getDaysRemaining();
            if($daysRemaining == 0){
                $daysRemaining = 3;
            }
            $dateNow = new \DateTime('now');
            $dayOfYearNow = $dateNow->format('z');
            $dayOfChangeStatusEvent = $checked + $daysRemaining;
            $isChangeStatusEvent = $dayOfChangeStatusEvent <= $dayOfYearNow;
        }

        if ($status and $isChangeStatusEvent) {
            $query = $this->entityManager->createQuery("
            UPDATE App\Entity\Event e 
            SET e.status = 0 
            WHERE e.status = $status"
            );
            $query->getResult();
        }
    }

    /**
     * @return mixed
     */
    public function changeOfStatusEvent()
    {
        return $this->entityManager->createQuery("
            SELECT e FROM App\Entity\Event e
            WHERE e.status = 1 AND e.name != 'telephone'
        ")
            ->getResult();
    }
}