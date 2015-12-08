<?php
namespace Gwa\Wordpress\Zero\Test\Theme;

class AbstractThemeSettingsTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $instance = new MyThemeSettings;
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Theme\AbstractThemeSettings', $instance);
    }
}
