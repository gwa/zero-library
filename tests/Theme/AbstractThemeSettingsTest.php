<?php
namespace Gwa\Wordpress\Zero\Test\Theme;

use Gwa\Wordpress\Zero\Theme\MenuFactory\MockMenuFactory;
use Gwa\Wordpress\MockeryWpBridge\MockeryWpBridge;

class AbstractThemeSettingsTest extends \PHPUnit_Framework_TestCase
{
    private $bridge;
    private $instance;

    public function setUp()
    {
        $this->bridge = new MockeryWpBridge;
        $this->instance = new MyThemeSettings;
        $this->instance->setMenuFactory(new MockMenuFactory);
        $this->instance->setWpBridge($this->bridge);
    }

    /* ---------------- */

    public function testConstruct()
    {
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Theme\AbstractThemeSettings', $this->instance);
    }

    public function testInit()
    {
        $this->mockBridgeForInit();
        $this->instance->init();

        $this->assertTrue($this->instance->isinit);
    }

    public function testGetContext()
    {
        $this->mockBridgeForInit();
        $this->instance->init();

        $data = $this->instance->addToContext([]);

        $this->assertInternalType('array', $data);
    }

    public function testGetDefaultMenuFactory()
    {
        $this->instance = new MyThemeSettings;
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Theme\MenuFactory\TimberMenuFactory', $this->instance->getMenuFactory());
    }

    /* --------- */

    private function mockBridgeForInit()
    {
        $this->bridge->mock()
            ->shouldReceive('getTemplateDirectory')
            ->andReturn('/my/path')
            ->shouldReceive('loadThemeTextdomain')
            ->with('mytheme', '/my/path/lang')
            ->shouldReceive('addImageSize')
            ->with('thumbnail', 300, 300, true)
            ->shouldReceive('registerNavMenus')
            ->mock();
    }
}
