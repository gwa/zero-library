# Menus

Menus are initiated in the Theme class using the `AbstractTheme::registerMenus()` method.

```php
<?php
// In Theme class
protected function doInit()
{
    $this->registerMenus([
        'header' => 'Header Menu'
    ]);
}
```

A TimberMenu instance will be available in the View context as `menu_header`.
