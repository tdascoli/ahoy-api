<?php
declare(strict_types=1);

namespace App\Domain\Profile;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ProfileNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Profile you requested does not exist.';
}
