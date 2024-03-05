# Setup for WordPress

WordPress does not use autoloading with Composer, therefore it has to be initialized first. Add the following lines to
your `wp-config.php`:

```php
require_once $_SERVER['DDEV_COMPOSER_ROOT'] . '/vendor/autoload.php';
ddev();
```
