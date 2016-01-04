<?php
namespace Gwa\Wordpress\Zero\Test\Shortcode;

use Gwa\Wordpress\WpBridge\MockeryWpBridge;

class AbstractModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $bridge = new MockeryWpBridge;
        $bridge->mock()
            ->shouldReceive('addShortcode')
            ->shouldReceive('shortcodeAtts')
            ->mock();
        $module = new \stdClass;
        $instance = new MyShortcode;

        $this->assertInstanceOf('Gwa\Wordpress\Zero\Shortcode\AbstractShortcode', $instance);

        $instance->init($bridge, $module);
        $this->assertEquals($module, $instance->getModule());

        $output = $instance->render(['foo' => 'bar'], '');
    }
}
