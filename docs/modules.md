# Modules

Modules provide _encapsulated functionality_ for the theme.

Modules reside in their own file or directory within the `lib/Module` directory.

## Module slug

Each module should have a unique _slug_.

## Module functionality

Modules essentially add:

* Theme customizations (settings)
* Shortcodes
* Actions
* Filters
* Data to the context

## A basic module

```php
<?php
namespace MyThemeNamespace\Module\MyModule;

use Gwa\Wordpress\Zero\Module\AbstractThemeModule;

class MyModule extends AbstractThemeModule
{
    /**
     * @var string $slug
     */
    protected $slug = 'mymodule';

    /**
     * @return array
     */
    public function getContext()
    {
        return [];
    }
}
```

## Theme aware

Modules are _theme aware_, which means they have access to the Theme instance.

```php
<?php
// in a module
$theme = $this->getTheme();

$environment = $this->getTheme()->getEnvironment();
$textdomain = $this->getTheme()->getTextDomain();

$i18n_text = $this->getTheme()->__('mytext'); // Shortcut method to use text domain set in theme 
```

## Customization

To add settings and controls to the WP Customization screen, use the `Gwa\Wordpress\Zero\Traits\AddThemeCustomization` trait.

## Adding shortcodes

Create a Shortcode class in your Module directory.

Add the fully qualified class name to the array returned by the module's `getShortcodeClasses` method.

```php
protected function getShortcodeClasses()
{
    return [
        'MyThemeNamespace\Module\MyModule\Shortcodes\MyShortcode'
    ];
}
```

The shortcode is then available.

## Actions

[stub]

## Filters

[stub]

## Context data

Add data to the `array` returned by the `getContext` method, ideally using the _module slug_ as a prefix.

```php
<?php

/**
 * @return array
 */
public function getContext()
{
    return [
        $this->getSlug() . '_foo' => 'bar';
    ];
}
```
