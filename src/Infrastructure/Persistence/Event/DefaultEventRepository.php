<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Event;

use App\Domain\Event\Event;
use App\Domain\Event\QueueNotFoundException;
use App\Domain\Event\EventRepository;
use App\Infrastructure\Base\MySQLPersistence;

class DefaultEventRepository extends MySQLPersistence implements EventRepository
{
    
    /**
     * {@inheritdoc}
     */
    public function list(): array
    {
        /* Create a prepared statement */
        $stmt = $this->db->prepare("SELECT * FROM event");

        /* execute the query */
        $stmt->execute();

        /* fetch all results */
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $uid): Event
    {
        /* Create a prepared statement */
        $stmt = $this->db->prepare("SELECT * FROM event WHERE uid = :uid");

        /* execute the query */
        $stmt->execute(array(':uid' => $uid));

        /* fetch all results */
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($res)) {
            throw new QueueNotFoundException();
        }
        else if (count($res)>1){
            $this->logger->warning("more than one event found, uid should be unique - uid: ".$uid);
        }

        return Event::ofArray($res[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function post(object $object, ?int $id): Event {
        $event = Event::of($object);
        if (empty($id)){
            return $this->insert($event);
        }
        else {
            return $this->update($event, $id);
        }
    }

    private function insert(Event $event): Event {
        $stmt = $this->db->prepare('INSERT INTO event (title, profile_id, secret, date, active) VALUES (:title, :profile_id, :secret, :date, :active)');
        $stmt->execute(array(
            'title' => $event->getTitle(),
            'profile_id' => $event->getProfileId(),
            'secret' => $event->getSecret(),
            'date' => self::createDate(),
            'active' => $event->isActive()));
        if (!$stmt){
            // todo better exception
            throw new QueueNotFoundException();
        }
        return $this->get($this->db->lastInsertId());
    }

    private function update(Event $event, int $id): Event {
        $stmt = $this->db->prepare('UPDATE event SET title = :title, profile_id = :profile_id, secret = :secret, date = :date, active = :active WHERE uid = :uid');
        $result = $stmt->execute(array(
            'uid' => $id,
            'title' => $event->getTitle(),
            'profile_id' => $event->getProfileId(),
            'secret' => $event->getSecret(),
            'date' => $event->getDate(),
            'active' => $event->isActive()));
        if (!$result) {
            throw new QueueNotFoundException();
        }
        return $this->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(int $uid): bool {
        $stmt = $this->db->prepare('DELETE FROM event WHERE uid = :uid');

        $stmt->execute(['uid' => $uid]);

        $countDel = $stmt->rowCount();

        if ($countDel==0) {
            throw new QueueNotFoundException();
        }
        return ($countDel > 0);
    }
}
