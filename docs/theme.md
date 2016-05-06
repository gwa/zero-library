# The Theme

The theme must have a single Theme class.

The theme class extends `Gwa\Wordpress\Zero\Theme\AbstractTheme`.

The theme class must implement a `doInit` method.

```php
<?php
namespace MyThemeNamespace;

use Gwa\Wordpress\Zero\Theme\AbstractTheme;

class CustomTheme extends AbstractTheme
{
    public function doInit()
    {
        // initialize the theme functions
        $this->setViewsDirectory(realpath(dirname(__DIR__) . '/../views'));
    }
}
