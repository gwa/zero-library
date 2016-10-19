# Shortcodes

Shortcodes are defined in their own classes, and are initiated in Modules.

They should be contained in the module namespace.

```
lib/
    Module/
        MyModule/
            MyModule.php
            Shortcode/
                MyShortcode.php
```

## Shortcode class

This is a basic Shortcode class without attributes:

```php
<?php
namespace \Module\MyModule\Shortcode\MyShortcode;

use Gwa\Wordpress\Zero\Shortcode\AbstractShortcode;

class MyShortcode extends AbstractShortcode
{
    /**
     * {@inheritdoc}
     */
    public function getShortcode()
    {
        return 'myshortcode';
    }
    
    /**
     * {@inheritdoc}
     */
    public function render($atts, $content = '')
    {
        // Auto paragraphs in content
        // https://codex.wordpress.org/Function_Reference/wpautop
        $content = $this->getWpBridge()->wpautop($content);
        
        // Fancy punctuation, etc. (quotes)
        // https://codex.wordpress.org/Function_Reference/wptexturize
        $content = $this->getWpBridge()->wptexturize($content);
        
        // Nested shortcodes
        $content = $this->getWpBridge()->doShortcode($content);
        
        return sprintf('<div style="background: red">%s</div>', $content);
    }
}
```

## Attributes

```php
class MyShortcode extends AbstractShortcode
{
    // ...
    
    public function render($atts, $content = '')
    {
        $atts = $this->getNormedAtts($atts);
        
        return sprintf('<div style="background: %s">%s</div>', $atts['color'], $content);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getDefaultAtts()
    {
        return [
            'color' => 'red'
        ];
    }
}

```
