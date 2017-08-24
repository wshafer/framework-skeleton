<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Example Entity Class to store a simple timestamp to the database.
 *
 * @ORM\Entity
 * @ORM\Table(name="ping_log")
 */
class PingLog
{
    use TimestampableTrait;

    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
}
