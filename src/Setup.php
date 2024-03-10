<?php
declare(strict_types=1);

namespace TimonKreis\DDEVConfig;

/**
 * @package TimonKreis\DDEVConfig
 */
class Setup
{
    /**
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        if (!isset($_SERVER['IS_DDEV_PROJECT'])) {
            throw new \Error('Project seems to be no DDEV project!');
        }

        foreach (glob(__DIR__ . '/System/*.php') as $file) {
            $className = __NAMESPACE__ . '\System\\' . substr(basename($file), 0, -4);

            if (class_exists($className)) {
                /** @var AbstractSystem $instance */
                $instance = new $className($configuration);

                if ($instance->isApplicable()) {
                    $instance->setup();

                    return;
                }
            }
        }

        throw new \Error('Unable to setup system!');
    }
}
