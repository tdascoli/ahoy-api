<?php
declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\JsonHelper;
use JsonSerializable;

class EventLight extends JsonHelper implements JsonSerializable
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
    private $date;

    /**
     * @param int|null  $uid
     * @param string    $title
     * @param int       $date
     */
    public function __construct(?int $uid, string $title, int $date)
    {
        $this->uid = $uid;
        $this->title = $title;
        $this->date = $date;
    }

    public static function of(object $object): EventLight {
        return new EventLight(
            self::intvalundefined($object,"uid"),
            $object->title,
            self::timestamp($object->date));
    }

    public static function ofArray(array $object): EventLight {
        return new EventLight(
            self::intvalundefined($object,"uid"),
            $object['title'],
            self::timestamp($object['date']));
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
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'uid' => $this->uid,
            'title' => $this->title,
            'date' => $this->date
        ];
    }
}
