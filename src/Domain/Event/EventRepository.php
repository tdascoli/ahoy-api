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
     * @param int $profile_id
     * @return Event[]
     */
    public function listByProfileId(int $profile_id): array;

    /**
     * @param int $id
     * @return Event
     * @throws EventNotFoundException
     */
    public function get(int $id): Event;

    /**
     * @param object $event
     * @param int|null $id
     * @return Event
     * @throws EventNotFoundException
     */
    public function post(object $event, ?int $id): Event;

    /**
     * @param int $id
     * @return bool
     * @throws EventNotFoundException
     */
    public function remove(int $id): bool;
}
