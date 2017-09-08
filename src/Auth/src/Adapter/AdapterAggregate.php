<?php
declare(strict_types=1);

namespace Auth\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class AdapterAggregate implements PasswordInterface
{
    /** @var string|null */
    protected $username;

    /** @var string|null */
    protected $password;

    /** @var AdapterInterface[] */
    protected $adapters;

    public function __construct(array $adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return Result
     */
    public function authenticate()
    {
        foreach ($this->adapters as $adapter) {

            if ($adapter instanceof \Auth\Adapter\AdapterInterface) {
                $adapter->setUsername($this->username);
            }

            if ($adapter instanceof PasswordInterface) {
                $adapter->setPassword($this->password);
            }

            $result = $adapter->authenticate();

            if ($result->getCode() == Result::SUCCESS) {
                return $result;
            }
        }

        return new Result(Result::FAILURE, $this->username);
    }
}
