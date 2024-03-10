<?php
declare(strict_types=1);

/**
 * @noinspection PhpUnused
 * @param array $configuration
 */
function ddev(array $configuration = []): void {
    new TimonKreis\DDEVConfig\Setup($configuration);
}
