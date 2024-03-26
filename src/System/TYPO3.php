<?php
declare(strict_types=1);

namespace TimonKreis\DDEVConfig\System;

use TimonKreis\DDEVConfig\AbstractSystem;
use TYPO3\CMS\Core\Core\Environment;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\DDEVConfig\System
 */
class TYPO3 extends AbstractSystem
{
    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $_SERVER['DDEV_PROJECT_TYPE'] === 'typo3';
    }

    /**
     *
     */
    public function setup(): void
    {
        // Default database credentials
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['driver'] = $this->get('db/driver');
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['charset'] = $this->get('db/charset');
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = $this->get('db/host');
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] = $this->get('db/port');
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = $this->get('db/user');
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = $this->get('db/password');
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = $this->get('db/database');

        // Disable forcing SSL encryption for backend
        $GLOBALS['TYPO3_CONF_VARS']['BE']['lockSSL'] = false;

        // Reverse proxy configuration
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxySSL'] = '*';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxyIP'] = '*';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = $_SERVER['HTTP_HOST'] ?? '.*';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxyHeaderMultiValue'] = 'first';

        // SMTP configuration
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'] = $this->get('mail/from/name');
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] = $this->get('mail/from/address');
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = 'smtp';
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_server'] = $this->get('mail/smtp/host');
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_username'] = $this->get('mail/smtp/user');
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_password'] = $this->get('mail/smtp/password');
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_encrypt'] = false;

        if ((int)$this->get('mail/smtp/port') !== 25) {
            $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_server'] .= ':' . $this->get('mail/smtp/port');
        }

        // Allow HTTP requests for non-secure URLs
        $GLOBALS['TYPO3_CONF_VARS']['HTTP']['verify'] = false;

        // Dummy password for install tool to prevent 404 errors
        $GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '1';

        // Debugging
        if (Environment::getContext()->isDevelopment() || Environment::getContext()->isTesting()) {
            $GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] = true;
            $GLOBALS['TYPO3_CONF_VARS']['FE']['disableNoCacheParameter'] = false;
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '*';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = 1;
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['exceptionalErrors'] = 28674;
        }
    }

    /**
     * @return array
     */
    protected function getSystemDefaults(): array
    {
        return [];
    }
}
