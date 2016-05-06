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
