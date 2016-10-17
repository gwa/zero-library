# Environment

The environment variable is set via the `ZERO_ENV` apache environment variable.

The current environment is available throught the "theme" object's `getEnvironment()` method.

Any class that has the `HasTheme` trait can therefore access the current environment:

```php
<?php

switch ($this->getTheme()->getEnvironment()) {
  case 'production':
    // Do something
    break;
  case 'development':
    // Do something else
    break;
}
```
