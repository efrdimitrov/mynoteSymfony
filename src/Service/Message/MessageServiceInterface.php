<?php


namespace App\Service\Message;


use App\Entity\Event;

interface MessageServiceInterface
{
    public function newPhoneBill();

    public function telephone();

    public function payTelephoneProcess(int $id, Event $event);

    public function viewEvents();
}