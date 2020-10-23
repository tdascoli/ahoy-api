<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Queue;

use App\Domain\Queue\QueueNotFoundException;
use App\Domain\Queue\Queue;
use App\Domain\Queue\QueueRepository;
use App\Infrastructure\Base\MySQLPersistence;
use PDO;

class DefaultQueueRepository extends MySQLPersistence implements QueueRepository
{
    /**
     * {@inheritdoc}
     */
    public function list(int $event_id): array
    {
        /* Create a prepared statement */
        $stmt = $this->db->prepare("SELECT * FROM queue WHERE event_uid = :event_id");

        /* execute the query */
        $stmt->execute(array(':event_id' => $event_id));

        /* execute the query */
        $stmt->execute();

        /* fetch all results */
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $uid): Queue
    {
        /* Create a prepared statement */
        $stmt = $this->db->prepare("SELECT * FROM queue WHERE uid = :uid");

        /* execute the query */
        $stmt->execute(array(':uid' => $uid));

        /* fetch all results */
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($res)) {
            throw new QueueNotFoundException();
        }
        else if (count($res)>1){
            $this->logger->warning("more than one Queue found, uid should be unique - uid: ".$uid);
        }

        return Queue::ofArray($res[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function post(object $object, int $event_id): Queue {
        $queue = Queue::of($object);
        if (empty($id)){
            return $this->insert($queue);
        }
        else {
            return $this->update($queue, $id);
        }
    }

    /**
     * @param Queue $queue
     * @return Queue
     * @throws QueueNotFoundException
     */

    private function insert(Queue $queue): Queue {
        $stmt = $this->db->prepare('INSERT INTO queue (event_id, firstname, lastname, address, birthday, mobile, email) VALUES (:event_id, :firstname, :lastname, :address, :birthday, :mobile, :email)');
        $stmt->execute(array(
            'event_id' => $queue->getEventId(),
            'firstname' => $queue->getFirstname(),
            'lastname' => $queue->getLastname(),
            'address' => $queue->getAddress(),
            'birthday' => $queue->getBirthday(),
            'mobile' => $queue->getMobile(),
            'email' => $queue->getEmail()));
        if (!$stmt){
            // todo better exception
            throw new QueueNotFoundException();
        }
        return $this->get((int) $this->db->lastInsertId());
    }

    private function update(Queue $queue, int $id): Queue {
        $stmt = $this->db->prepare('UPDATE queue SET event_id = :event_id, firstname = :firstname, lastname = :lastname, address = :address, birthday = :birthday, mobile = :mobile, email = :email WHERE uid = :uid');
        $result = $stmt->execute(array(
            'uid' => $id,
            'event_id' => $queue->getEventId(),
            'firstname' => $queue->getFirstname(),
            'lastname' => $queue->getLastname(),
            'address' => $queue->getAddress(),
            'birthday' => $queue->getBirthday(),
            'mobile' => $queue->getMobile(),
            'email' => $queue->getEmail()
        ));
        if (!$result) {
            throw new QueueNotFoundException();
        }
        return $this->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(int $uid): bool {
        $stmt = $this->db->prepare('DELETE FROM queue WHERE uid = :uid');

        $stmt->execute(['uid' => $uid]);

        $countDel = $stmt->rowCount();

        if ($countDel==0) {
            throw new QueueNotFoundException();
        }
        return ($countDel > 0);
    }
}
