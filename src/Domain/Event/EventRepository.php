<?php
declare(strict_types=1);

namespace App\Domain\Event;

interface EventRepository
{
    /**
     * @return Event[]
     */
    public function list(): array;

    /**
     * @param int $id
     * @return Event
     * @throws QueueNotFoundException
     */
    public function get(int $id): Event;

    /**
     * @param object $event
     * @param int|null $id
     * @return string
     * @throws QueueNotFoundException
     */
    public function post(object $event, ?int $id): Event;

    /**
     * @param int $id
     * @return bool
     * @throws QueueNotFoundException
     */
    public function remove(int $id): bool;
}
