<?php
declare(strict_types=1);

namespace TimonKreis\DDEVProjectConfig\System;

use TimonKreis\DDEVProjectConfig\AbstractSystem;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\DDEVProjectConfig
 */
class WordPress extends AbstractSystem
{
    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $_SERVER['DDEV_PROJECT_TYPE'] === 'wordpress';
    }

    /**
     * @return array
     */
    protected function getSystemDefaults(): array
    {
        return [
            'wpms' => true,
            'mail/mailer' => 'smtp',
            'mail/smtp/auth' => false,
            'mail/smtp/autotls' => true,
            'mail/ssl' => '',
        ];
    }

    /**
     *
     */
    public function setup(): void
    {
        /**
         * @param string $name
         * @param string $key
         */
        $define = function(string $name, string $key): void {
            defined($name) || define($name, $this->get($key));
        };

        // Default database credentials
        $define('DB_HOST', 'db/host');
        $define('DB_USER', 'db/user');
        $define('DB_PASSWORD', 'db/password');
        $define('DB_NAME', 'db/database');

        // SMTP configuration (based on plugin "WP Mail SMTP")
        /** @see https://de.wordpress.org/plugins/wp-mail-smtp/ */
        $define('WPMS_ON', 'wpms');
        $define('WPMS_MAILER', 'mail/mailer');
        $define('WPMS_SMTP_HOST', 'mail/smtp/host');
        $define('WPMS_SMTP_PORT', 'mail/smtp/port');
        $define('WPMS_SMTP_AUTH', 'mail/smtp/auth');
        $define('WPMS_SMTP_AUTOTLS', 'mail/smtp/autotls');
        $define('WPMS_SMTP_USER', 'mail/smtp/user');
        $define('WPMS_SMTP_PASS', 'mail/smtp/password');
        $define('WPMS_SSL', 'mail/ssl');
        $define('WPMS_MAIL_FROM_NAME', 'mail/from/name');
        $define('WPMS_MAIL_FROM', 'mail/from/address');
    }
}
