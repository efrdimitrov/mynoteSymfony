<?php

namespace App\Service\Message;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

class MessageService implements MessageServiceInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MessageService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    public function newPhoneBill()
    {
        $telephone = $this->telephone();
        $getIdPhone = $telephone[0]->getId();
        $dateTelephone = $telephone[0]->getDate();
        $dateTelephone = $dateTelephone->format('Y-m-d');
        $dateNow = new \DateTime('now');
        $dateNow = $dateNow->format('Y-m-d');

        if (strtotime($dateTelephone) < strtotime($dateNow) and $this->$telephone[0]->getCategory() == 'платен') {
            $query = $this->entityManager->createQuery("
            UPDATE App\Entity\Event e 
            SET e.category = 'неплатен' 
            WHERE e.id = :id "
            )->setParameter('id', $getIdPhone);
            $query->getResult();
        }
    }

    /**
     * @return mixed
     */
    public function telephone()
    {
        return $this->entityManager->createQuery("
            SELECT e FROM App\Entity\Event e
            WHERE e.name = 'telephone'
        ")
            ->getResult();
    }

    /**
     * @param int $id
     * @param Event $event
     */
    public function payTelephoneProcess(int $id, Event $event)
    {
        $date = $event->getDate();
        $date->modify('+1 month');
        $nextDate = $date->format('Y-m-d');

        $this->entityManager->createQuery("
            UPDATE App\Entity\Event e 
            SET e.category = 'платен', e.date = '$nextDate'
            WHERE e.id = :id")
            ->setParameter('id', $id)
            ->getResult();
    }

    /**
     * @return mixed
     */
    public function viewEvents()
    {
        return $this->entityManager->createQuery("
            SELECT e FROM App\Entity\Event e
            WHERE e.status != '1' and YEAR(e.date) <= YEAR(CURRENT_DATE()) + 0.2
            ORDER BY Month(e.date), Day(e.date)
        ")
            ->getResult();
    }


}