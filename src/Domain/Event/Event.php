<?php
declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\JsonHelper;
use JsonSerializable;

class Event extends JsonHelper implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $uid;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $profile_id;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var int
     */
    private $date;

    /**
     * @var int|bool
     */
    private $active;

    /**
     * @param int|null  $uid
     * @param string    $title
     * @param int       $profile_id
     * @param string    $secret
     * @param int       $date
     * @param int|bool  $active
     */
    public function __construct(?int $uid, string $title, int $profile_id, string $secret, int $date, int $active)
    {
        $this->uid = $uid;
        $this->title = $title;
        $this->profile_id = $profile_id;
        $this->secret = $secret;
        $this->date = $date;
        $this->active = $active;
    }

    // todo date is not correct!! -> not an int because of - in data!! check docs

    public static function of(object $object): Event {
        return new Event(
            self::intvalundefined($object,"uid"),
            $object->title,
            intval($object->profile_id),
            $object->secret,
            intval($object->date),
            self::booleanval($object->active));
    }

    public static function ofArray(array $object): Event {
        return new Event(
            self::intvalundefined($object,"uid"),
            $object['title'],
            intval($object['profile_id']),
            $object['secret'],
            intval($object['date']),
            self::booleanval($object['active']));
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getProfileId(): int
    {
        return $this->profile_id;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @return int|bool
     */
    public function isActive(): int
    {
        return $this->active;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'uid' => $this->uid,
            'title' => $this->title,
            'profile_id' => $this->profile_id,
            'secret' => $this->secret,
            'date' => $this->date,
            'active' => $this->active
        ];
    }
}
