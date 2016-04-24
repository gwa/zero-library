<?php
namespace Gwa\Wordpress\Zero\Test\Theme;

use Gwa\Wordpress\Zero\Theme\AbstractTheme;

class MyTheme extends AbstractTheme
{
    /**
     * Used for testing.
     * @var boolean $isinit
     */
    public $isinit = false;

    protected $textdomain = 'mytheme';

    public function doInit()
    {
        $this->addImageSize('thumbnail', 300, 300, true);

        $this->registerMenus([
            'header' => 'Header Menu'
        ]);

        $this->isinit = true;
    }

    /**
     * @return array
     */
    protected function getModuleClasses()
    {
        $classes = parent::getModuleClasses();
        $classes[] = 'Gwa\Wordpress\Zero\Test\Module\MyModule';
        return $classes;
    }

    /**
     * @return array
     */
    protected function getThemeContext()
    {
        $context = parent::getThemeContext();
        $context['theme'] = 'mytheme';
        return $context;
    }
}
