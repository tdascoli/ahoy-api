<?php
declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\DomainException\DomainRecordNotFoundException;

class QueueNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Event you requested does not exist.';
}
