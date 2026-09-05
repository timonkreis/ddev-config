<?php
declare(strict_types=1);

/**
 * @noinspection PhpUnused
 */
function ddev(array $configuration = []): void {
    new TimonKreis\DDEVConfig\Setup($configuration);
}
