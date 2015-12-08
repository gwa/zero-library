<?php
namespace Gwa\Wordpress\Zero\Test\Controller;

class AbstractControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $controller = new MyController;
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Controller\AbstractController', $controller);
    }
}
