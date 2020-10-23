<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Event;

use App\Domain\Event\Event;
use App\Domain\Event\EventLight;
use App\Domain\Event\EventNotFoundException;
use App\Domain\Event\EventRepository;
use App\Infrastructure\Base\MySQLPersistence;
use Cake\Chronos\Chronos;
use PDO;

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

    public function listByProfileId(int $profile_id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM event WHERE profile_id = :profile_id");
        $stmt->execute(array(':profile_id' => $profile_id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            throw new EventNotFoundException();
        }
        else if (count($res)>1){
            $this->logger->warning("more than one event found, uid should be unique - uid: ".$uid);
        }
        return Event::ofArray($res[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveEvent(int $uid): EventLight
    {
        $today = Chronos::create()->startOfDay()->toDateTimeString();
        $tomorrow = Chronos::create()->addDay(1)->endOfDay()->toDateTimeString();
        /* Create a prepared statement */
        $stmt = $this->db->prepare("SELECT * FROM event WHERE uid = :uid AND active = 1 AND `date` BETWEEN :today AND :tomorrow");

        /* execute the query */
        $stmt->execute(array(
            ':uid' => $uid,
            'today' => $today,
            'tomorrow' => $tomorrow));

        /* fetch all results */
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($res)) {
            throw new EventNotFoundException();
        }
        else if (count($res)>1){
            $this->logger->warning("more than one event found, uid should be unique - uid: ".$uid);
        }
        return EventLight::ofArray($res[0]);
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
        $stmt = $this->db->prepare('INSERT INTO event (title, profile_id, secret, date, active) VALUES (:title, :profile_id, :secret, FROM_UNIXTIME(:date), :active)');
        $stmt->execute(array(
            'title' => $event->getTitle(),
            'profile_id' => $event->getProfileId(),
            'secret' => $event->getSecret(),
            'date' => $event->getDate(),
            'active' => $event->isActive()));
        if (!$stmt){
            // todo better exception
            throw new EventNotFoundException();
        }
        return $this->get((int) $this->db->lastInsertId());
    }

    private function update(Event $event, int $id): Event {
        $stmt = $this->db->prepare('UPDATE event SET title = :title, profile_id = :profile_id, secret = :secret, date = FROM_UNIXTIME(:date), active = :active WHERE uid = :uid');
        $result = $stmt->execute(array(
            'uid' => $id,
            'title' => $event->getTitle(),
            'profile_id' => $event->getProfileId(),
            'secret' => $event->getSecret(),
            'date' => $event->getDate(),
            'active' => $event->isActive()));
        if (!$result) {
            throw new EventNotFoundException();
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
            throw new EventNotFoundException();
        }
        return ($countDel > 0);
    }
}
