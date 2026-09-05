<?php
declare(strict_types=1);

namespace TimonKreis\DDEVConfig;

abstract class AbstractSystem
{
    /**
     * @var array<string, mixed>
     */
    private $_configuration;

    /**
     * @param array<string, mixed> $configuration
     */
    public function __construct(array $configuration)
    {
        $this->_configuration = $configuration;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getGlobalDefaults(): array
    {
        return [
            'db/driver' => 'mysqli',
            'db/charset' => 'utf8',
            'db/host' => 'db',
            'db/port' => 3306,
            'db/user' => 'db',
            'db/database' => 'db',
            'db/password' => 'db',

            'mail/from/name' => $_SERVER['DDEV_SITENAME'],
            'mail/from/address' => 'noreply@' . $_SERVER['DDEV_PROJECT'] . '.' . $_SERVER['DDEV_TLD'],
            'mail/smtp/host' => '127.0.0.1',
            'mail/smtp/port' => 1025,
            'mail/smtp/user' => 'noreply@' . $_SERVER['DDEV_PROJECT'] . '.' . $_SERVER['DDEV_TLD'],
            'mail/smtp/password' => '123456',
        ];
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function get(string $key)
    {
        if (isset($this->_configuration[$key])) {
            return $this->_configuration[$key];
        }

        if (isset($this->getSystemDefaults()[$key])) {
            return $this->getSystemDefaults()[$key];
        }

        if (isset($this->getGlobalDefaults()[$key])) {
            return $this->getGlobalDefaults()[$key];
        }

        throw new \Error('Configuration key "' . $key . '" does not exist!');
    }

    abstract public function isApplicable(): bool;

    /**
     * @return array<string, mixed>
     */
    abstract protected function getSystemDefaults(): array;

    abstract public function setup(): void;
}
