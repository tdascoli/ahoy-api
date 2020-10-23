<?php
declare(strict_types=1);

namespace App\Domain\Queue;

use App\Domain\JsonHelper;
use JsonSerializable;

class Queue extends JsonHelper implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $uid;

    /**
     * @var int
     */
    private $event_id;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $address;

    /**
     * @var int
     */
    private $birthday;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @param int|null  $uid
     * @param int       $event_id
     * @param string    $firstname
     * @param string    $lastname
     * @param string    $address
     * @param int       $birthday
     * @param string    $mobile
     * @param string    $email
     * @param int       $timestamp
     */
    public function __construct(?int $uid, int $event_id, string $firstname, string $lastname, string $address, int $birthday, string $mobile, string $email, int $timestamp)
    {
        $this->uid = $uid;
        $this->event_id = $event_id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->birthday = $birthday;
        $this->mobile = $mobile;
        $this->email = $email;
        $this->timestamp = $timestamp;
    }

    public static function of(object $object): Queue {
        return new Queue(
            self::intvalundefined($object,"uid"),
            intval($object->event_id),
            $object->firstname,
            $object->lastname,
            $object->address,
            intval($object->birthday),
            $object->mobile,
            $object->email,
            intval($object->timestamp));
    }

    public static function ofArray(array $object): Queue {
        return new Queue(
            self::intvalundefined($object,"uid"),
            intval($object['event_id']),
            $object['firstname'],
            $object['lastname'],
            $object['address'],
            intval($object['birthday']),
            $object['mobile'],
            $object['email'],
            intval($object['timestamp']));
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->uid;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int
     */
    public function getBirthday(): int
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'uid' => $this->uid,
            'event_id' => $this->event_id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'address' => $this->address,
            'birthday' => $this->birthday,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'timestamp' => $this->timestamp
        ];
    }
}
