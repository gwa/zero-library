# Controllers

A Controller is instantiated in a WP theme php file.

```php
<?php
// /theme/index.php
<?php
$mytheme->createController('MyThemeNamespace\Controller\Index')
    ->render();
```

There is therefore one Controller created per request.

After the Controller is created, the page is rendered via the `render` method.

## The Controller class

The Controller class gathers data for the _context_ that is passed to the View.

This is done in the `getContext` method.

The Controller has to define a View that should receive the context in the form of a path to a _Twig Template_.

The template is defined in the `getTemplates` method.

Both methods must be defined in a Controller class.

Here is the basic "index" controller called from the WP theme php file above.

```php
<?php
namespace MyThemeNamespace\Controller;

use Gwa\Wordpress\Zero\Controller\AbstractController;

/**
 * Displays the homepage.
 */
class Index extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return [
            'posts' => $this->getPosts(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplates()
    {
        return ['index.twig'];
    }
}

```

`getContext` gathers an array of "post objects" from WordPress, and assigns the to the variable `posts`.

`getTemplates` sets `index.twig` as the View.
