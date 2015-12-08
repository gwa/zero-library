<?php
namespace Gwa\Wordpress\Zero\Test\Theme;

use Gwa\Wordpress\Zero\Theme\HookManager;

class HookManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $instance = new HookManager;
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Theme\HookManager', $instance);
    }
}
