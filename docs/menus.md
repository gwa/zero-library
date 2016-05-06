# Menus

Menus are initiated in the Theme class using the `AbstractTheme::registerMenus()` method.

```php
<?php
// In Theme class
protected function doInit()
{
    $this->registerMenus([
        'header_menu' => 'Header Menu'
    ]);
}
```
