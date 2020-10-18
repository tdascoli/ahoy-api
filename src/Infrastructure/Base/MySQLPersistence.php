<?php
namespace App\Infrastructure\Base;

use App\Domain\Auth\UpdateException;
use PDO;
use Psr\Log\LoggerInterface;

abstract class MySQLPersistence extends BasePersistence
{
    /**
     * @var PDO Connection
     */
    public $db;

    /**
     * @var LoggerInterface $logger
     */
    public $logger;

    /**
     * MySQLPersistence constructor.
     * @param $db
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=ahoy', 'root', '');
        }
        catch(Exception $e) {
            $this->logger->warning($e);
        }
    }
}