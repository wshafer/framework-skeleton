<?php

namespace Database\Event;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\EntityRepository;

class FetchRepositoryEvent extends EventArgs
{
    const EVENT_NAME = 'fetchRepositoryEvent';

    protected $repository;

    /**
     * @return mixed
     */
    public function getRepository() : EntityRepository
    {
        return $this->repository;
    }

    /**
     * @param EntityRepository $repository
     */
    public function setRepository(EntityRepository $repository): void
    {
        $this->repository = $repository;
    }
}
