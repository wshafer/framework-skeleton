<?php

namespace DatabaseTest\Entity;

use Database\Entity\TimestampableTrait;
use PHPUnit\Framework\TestCase;

class TimestampableTraitTest extends TestCase
{
    /** @var TimestampableTrait */
    protected $entity;

    public function setup()
    {
        $this->entity = $this->getMockForTrait(TimestampableTrait::class);
    }

    public function testGetCreated()
    {
        $this->assertNull($this->entity->getCreated());
    }

    public function testGetModified()
    {
        $this->assertNull($this->entity->getModified());
    }
}
