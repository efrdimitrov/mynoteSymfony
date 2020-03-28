<?php


namespace App\Service\Event;


use App\Entity\Event;

interface EventServiceInterface
{
    /**
     * @return Event[]
     */
    public function getAll(): array;

    public function get(int $id): Event;

    public function getLast() : Event;

    public function save(Event $event);

    public function viewEvents();

    public function telephone();

    public function queryEvent();

    public function hiddenEvents();
}