<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Profile;

use App\Domain\Profile\Profile;
use App\Domain\Profile\ProfileNotFoundException;
use App\Domain\Profile\ProfileRepository;
use App\Infrastructure\Base\MySQLPersistence;
use PDO;

class PersistenceProfileRepository extends MySQLPersistence implements ProfileRepository
{
    private static $TYPE="profile";

    /**
     * {@inheritdoc}
     */
    public function list(): array
    {
        /* Create a prepared statement */
        $stmt = $this->db->prepare("SELECT * FROM profile");

        /* execute the query */
        $stmt->execute();

        /* fetch all results */
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $uid): Profile
    {
      /* Create a prepared statement */
      $stmt = $this->db->prepare("SELECT * FROM profile WHERE uid = :uid");

      /* execute the query */
      $stmt->execute(array(':uid' => $uid));

      /* fetch all results */
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (empty($res)) {
          throw new ProfileNotFoundException();
      }
      else if (count($res)>1){
        $this->logger->warning("more than one profile found, uid should be unique - uid: ".$uid);
      }

      return Profile::ofArray($res[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function getByDeviceId(string $device_id): Profile
    {
        /* Create a prepared statement */
        $stmt = $this->db->prepare("SELECT * FROM profile WHERE device_id = :device_id");

        /* execute the query */
        $stmt->execute(array(':device_id' => $device_id));

        /* fetch all results */
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($res)) {
            throw new ProfileNotFoundException();
        }
        else if (count($res)>1){
            $this->logger->warning("more than one profile found, uid should be unique - device_id: ".$device_id);
        }

        return Profile::ofArray($res[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function post(object $profileObject, ?int $profile_id): Profile {
      $profile = Profile::of($profileObject);
      $deviceHasAlreadyProfile = $this->deviceHasAlreadyProfile($profile->getDeviceId());
      if ($deviceHasAlreadyProfile){
          return $this->updateProfilePassword($profile->getPassword(), $profile->getDeviceId());
      }
      else if (empty($profile_id)){
        return $this->insertProfile($profile);
      }
      else {
        return $this->updateProfile($profile, $profile_id);
      }
    }

    private function insertProfile(Profile $profile): Profile {
      $stmt = $this->db->prepare('INSERT INTO profile (device_id, password) VALUES (:device_id, :password)');
      $stmt->execute(array(
              'device_id' => $profile->getDeviceId(),
              'password' => $profile->getPassword()));
      if (!$stmt){
          // todo better exception
          throw new ProfileNotFoundException();
      }
      return $this->getByDeviceId($profile->getDeviceId());
    }

    private function updateProfile(Profile $profile, int $profile_id): Profile {
      $stmt = $this->db->prepare('UPDATE profile SET device_id = :device_id, password = :password, active = :active, trial = :trial WHERE uid = :uid');
      $result = $stmt->execute(array(
              'uid' => $profile_id,
              'device_id' => $profile->getDeviceId(),
              'password' => $profile->getPassword(),
              'active' => $profile->isActive(),
              'trial' => $profile->isTrial()));
      if (!$result) {
          throw new ProfileNotFoundException();
      }
      return $this->get($profile_id);
    }

    private function updateProfilePassword(string $password, string $device_id): Profile {
        $stmt = $this->db->prepare('UPDATE profile SET password = :password, active = :active WHERE device_id = :device_id');
        $result = $stmt->execute(array(
            'device_id' => $device_id,
            'password' => $password,
            'active' => true));
        if (!$result) {
            throw new ProfileNotFoundException();
        }
        return $this->getByDeviceId($device_id);
    }

    private function deviceHasAlreadyProfile(string $deviceId): bool {
        $stmt = $this->db->prepare('SELECT COUNT(uid) as count FROM profile WHERE device_id = :device_id');
        $stmt->execute(array('device_id' => $deviceId));
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ($res[0]['count']==1);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(int $uid): bool {
      $stmt = $this->db->prepare('DELETE FROM profile WHERE uid = :uid');

      $stmt->execute(['uid' => $uid]);

      $countDel = $stmt->rowCount();

      if ($countDel==0) {
          throw new ProfileNotFoundException();
      }
      return ($countDel > 0);
    }
}
