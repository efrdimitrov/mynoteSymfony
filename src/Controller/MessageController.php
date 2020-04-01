<?php

namespace App\Controller;


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
     * @return Response
     */
    public function payTelephone()
    {
        $this->messageService->payTelephoneProcess();
        return $this->redirectToRoute("events");
    }
}
