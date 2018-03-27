<?php

declare(strict_types=1);

namespace OAuth\Command\Scope;

use OAuth\Config\Config;
use OAuth\Repository\ScopeRepository;
use Symfony\Component\Console\Command\Command;

class AbstractScopeCommand extends Command
{
    /** @var ScopeRepository */
    protected $scopeRepository;

    protected $config;

    public function __construct(
        ScopeRepository $scopeRepository,
        Config $config
    ) {
        $this->scopeRepository = $scopeRepository;
        $this->config = $config;
        parent::__construct();
    }
}
