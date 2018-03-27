<?php
declare(strict_types=1);

namespace Database\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableTrait
{
    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true, options={"default" : NULL})
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true, options={"default" : NULL})
     */
    private $modified;

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }
}
