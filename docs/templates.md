# Creating a template

WordPress templates need a php file in the theme folder.

Like any other WP theme file, the template file creates a controller through the theme instance, and renders the page.

To be made available as a WP template, the template doc comments need to be included at the top of the file.

```php
<?php
// File /theme/template-aboutus.php
/**
 * Template Name: About Us
 * Description: The custom about us page.
 */

$mytheme->createController('MyThemeNamespace\Controller\Page')
    ->render();
```

## Single controller for multiple templates

If the only difference is the View (Twig template), a single Controller can be used that sets a View template using the WP template chosen by the author.

The above WP php file uses the default Page Controller below.

```php
<?php
namespace MyThemeNamespace\Controller;

use Gwa\Wordpress\Zero\Controller\AbstractController;

/**
 * Displays a default page.
 */
class Page extends AbstractController
{
    public function getContext()
    {
        return [
            'post' => $this->getPost()
        ];
    }

    public function getTemplates()
    {
        $slug = $this->getTemplateSlug();
        $path = str_replace('template-', 'templates/', basename($slug, '.php')) . '.twig';

        return [
            $path,
            'page.twig'
        ];
    }
}
```

The `getTemplates` method uses the template slug to create a view path.

* `basename($slug, '.php')` returns `template-aboutus`. (`.php` is stripped off).
* `template-` is replaced with `templates/`
* the final Twig filepath is `templates/aboutus.twig`

If `templates/aboutus.twig` exists it will be used, if not the default `page.twig` is used.
