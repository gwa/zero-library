<?php
namespace Gwa\Wordpress\Zero\Test\Module;

class AbstractModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $instance = new MyModule;
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Module\AbstractThemeModule', $instance);
    }
}
