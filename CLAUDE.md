# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This is a Composer package (`timonkreis/ddev-config`) that provides default configuration for web projects running
inside a DDEV development environment. It is consumed via `composer require --dev timonkreis/ddev-config:@dev` and
exposes a single global `ddev()` function (autoloaded via `init.php`) that project bootstrap files call to apply
DDEV-specific defaults (database credentials, SMTP/mail settings, TYPO3/WordPress-specific tweaks).

There is no build step, test suite, or CI configuration in this repository — it is plain, dependency-free PHP
(`"php": ">=7.1"` in `composer.json`).

## Architecture

- `init.php` — defines the global `ddev(array $configuration = [])` function that instantiates `Setup`. This is the
  only public entry point consumers call from their project's bootstrap file (e.g. TYPO3's `additional.php` or
  WordPress's `wp-config.php`).
- `src/Setup.php` — on construction, verifies `$_SERVER['IS_DDEV_PROJECT']` is set (throws otherwise), then
  autodiscovers system implementations by globbing `src/System/*.php`. It instantiates each discovered class and
  calls `isApplicable()`; the first applicable system has `setup()` called and discovery stops. Throws if no system
  matches.
- `src/AbstractSystem.php` — base class for all systems. Provides `getGlobalDefaults()` (DB and mail defaults shared
  across all systems, several derived from DDEV environment variables like `DDEV_SITENAME`, `DDEV_PROJECT`,
  `DDEV_TLD`) and `get(string $key)`, which resolves a value with precedence: explicit `$configuration` passed to
  `ddev()` > `getSystemDefaults()` (system-specific overrides) > `getGlobalDefaults()`. Throws if the key exists in
  none of the three. Subclasses must implement `isApplicable()`, `getSystemDefaults()`, and `setup()`.
- `src/System/` — one class per supported project type, each extending `AbstractSystem`:
  - `TYPO3.php` — applicable when `DDEV_PROJECT_TYPE === 'typo3'`. Configures `$GLOBALS['TYPO3_CONF_VARS']`
    directly: DB connection, reverse proxy/SSL trust settings, SMTP mail transport, a dummy encryption key and
    install tool password (only if unset, to avoid overwriting real values), and dev-context-only debug settings
    (checked via `TYPO3\CMS\Core\Core\Environment`).
  - `WordPress.php` — applicable when `DDEV_PROJECT_TYPE === 'wordpress'`. Defines WordPress `DB_*` constants and
    `WPMS_*` constants for the "WP Mail SMTP" plugin, using a local `$define` helper that only calls `define()` if
    the constant isn't already defined (so explicit project config always wins).

To add support for a new system, add a new class to `src/System/` extending `AbstractSystem`; `Setup` picks it up
automatically via the glob — no registration step needed.

- `mocks/system/TYPO3.php` — a minimal stub of `TYPO3\CMS\Core\Core\Environment`/`ApplicationContext` used only so
  `src/System/TYPO3.php` type-checks/parses without the actual TYPO3 core installed as a dependency. It is not
  autoloaded by `composer.json` and is not wired into any test runner.
- `docs/typo3.md`, `docs/wordpress.md` — per-system setup instructions shown to consumers of this package (linked
  from `README.md`), documenting exactly where to call `ddev()` in each project type's bootstrap file.

## Conventions

- PHP files start with `declare(strict_types=1);`, per `~/.claude/docs/php.md` and the existing codebase style.
- Indentation is 4 spaces, LF line endings, UTF-8, per `.editorconfig`.
- Keep `CHANGELOG.md` updated (dated, categorized as Added/Changed/Fixed) when making user-facing changes — this is
  the project's established practice.
