<?php
declare(strict_types=1);

namespace App\Domain\Queue;

use App\Domain\Event\QueueNotFoundException;

interface QueueRepository
{
    /**
     * @param int $event_id
     * @return Queue[]
     */
    public function list(int $event_id): array;

    /**
     * @param int $id
     * @return Queue
     * @throws QueueNotFoundException
     */
    public function get(int $id): Queue;

    /**
     * @param object $queue
     * @param int|null $id
     * @return string
     * @throws QueueNotFoundException
     */
    public function post(object $queue, int $event_id): Queue;

    /**
     * @param int $id
     * @return bool
     * @throws QueueNotFoundException
     */
    public function remove(int $id): bool;
}
