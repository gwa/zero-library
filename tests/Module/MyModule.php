<?php
namespace Gwa\Wordpress\Zero\Test\Module;

use Gwa\Wordpress\Zero\Module\AbstractThemeModule;

class MyModule extends AbstractThemeModule
{
    public $isinit = false;

    protected function doInit()
    {
        $this->isinit = true;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return ['foo' => 'bar'];
    }

    /**
     * @return array
     */
    protected function getShortcodeClasses()
    {
        return [
            'Gwa\Wordpress\Zero\Test\Shortcode\MyShortcode'
        ];
    }
}
