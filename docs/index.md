# Zero Library

The Zero Library is a development framework for WordPress themes.

## composer.json

## Theme directory setup

```
src/
    frontend/
    lib/
    views/
tests/

theme/
    languages/
    functions.php
    index.php
    style.css
```

## theme/functions.php

The `functions.php` initializes the theme.

```php
<?php
use Gwa\Wordpress\WpBridge\WpBridge;
use MyThemeNamespace\CustomTheme;

// Create and initialize the theme
$mytheme = new CustomTheme(ZERO_ENV);
$mytheme->setWpBridge(new WpBridge)->init();

// $mytheme is now available in the global namespace
```
