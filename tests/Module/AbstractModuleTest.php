<?php
namespace Gwa\Wordpress\Zero\Test\Module;

use Gwa\Wordpress\WpBridge\MockeryWpBridge;
use Gwa\Wordpress\Zero\Theme\HookManager;

class AbstractModuleTest extends \PHPUnit_Framework_TestCase
{
    private $bridge;
    private $hookmanager;
    private $instance;

    public function setUp()
    {
        $this->bridge = new MockeryWpBridge;
        $this->hookmanager = new HookManager;
        $this->hookmanager->setWpBridge($this->bridge);
        $this->instance = new MyModule;
    }

    /* ---------------- */

    public function testConstruct()
    {
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Module\AbstractThemeModule', $this->instance);
    }

    public function testBasicModule()
    {
        $module = new BasicModule;
        $module->init($this->bridge, $this->hookmanager);

        $this->assertInternalType('array', $module->getContext());

        $context = $module->getContext();
        
        $this->assertTrue(empty($context));
        $this->assertEquals('basic', $module->getSlug());
    }

    public function testInit()
    {
        $this->instance->init($this->bridge, $this->hookmanager);
        $this->assertTrue($this->instance->isinit);
    }

    public function testGetContext()
    {
        $this->assertInternalType('array', $this->instance->getContext());
    }
}
