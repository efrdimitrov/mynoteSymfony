<?php

namespace App\Controller;

use App\Entity\Event;
use App\Service\Message\MessageServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;



class MessageController extends AbstractController
{

    /**
     * @var MessageServiceInterface
     */
    private $messageService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MessageController constructor.
     * @param MessageServiceInterface $messageService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MessageServiceInterface $messageService,
                                EntityManagerInterface $entityManager)
    {
        $this->messageService = $messageService;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/pay_telephone/{id}", name="pay_telephone")
     *
     * @param int $id
     * @param Event $event
     * @return Response
     */
    public function pay_telephone(int $id, Event $event)
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

        return $this->redirectToRoute("events");
    }
}
