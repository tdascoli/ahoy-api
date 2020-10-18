<?php
declare(strict_types=1);

namespace App\Domain\Profile;

use App\Domain\JsonHelper;
use JsonSerializable;

class Profile extends JsonHelper implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $uid;

    /**
     * @var string
     */
    private $device_id;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $create_date;

    /**
     * @var int|bool
     */
    private $active;

    /**
     * @var int|bool
     */
    private $trial;

    /**
     * @param int|null  $uid
     * @param string    $device_id
     * @param string    $password
     * @param int       $create_date
     * @param int|bool  $active
     * @param int|bool  $trial
     */
    public function __construct(?int $uid, string $device_id, string $password, int $create_date, int $active, int $trial) {
        $this->uid = $uid;
        $this->device_id = $device_id;
        $this->password = $password;
        $this->create_date = $create_date;
        $this->active = $active;
        $this->trial = $trial;
    }

    public static function of(object $profile): Profile {
        return new Profile(
            self::intvalundefined($profile,"uid"),
            $profile->device_id,
            $profile->password,
            intval($profile->create_date),
            self::booleanval($profile->active),
            self::booleanval($profile->trial));
    }

    public static function ofArray(array $profile): Profile {
        return new Profile(
            self::intvalundefined($profile,"uid"),
            $profile['device_id'],
            $profile['password'],
            intval($profile['create_date']),
            self::booleanval($profile['active']),
            self::booleanvalundefined($profile,'trial',1));
    }

    /**
     * @return int|null
     */
    public function getUid(): ?int
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getDeviceId(): string
    {
        return $this->device_id;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getCreateDate(): int
    {
        return $this->create_date;
    }

    /**
     * @return int|bool
     */
    public function isActive(): int
    {
        return $this->active;
    }

    /**
     * @return int|bool
     */
    public function isTrial(): int
    {
        return $this->trial;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'uid' => $this->uid,
            'device_id' => $this->device_id,
            'password' => $this->password,
            'create_date' => $this->create_date,
            'active' => $this->active,
            'trial' => $this->trial
        ];
    }
}
