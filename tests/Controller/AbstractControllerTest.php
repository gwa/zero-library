<?php
namespace Gwa\Wordpress\Zero\Test\Controller;

use Gwa\Wordpress\Zero\Timber\MockeryTimberBridge;
use Gwa\Wordpress\WpBridge\MockeryWpBridge;

class AbstractControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $controller = new MyController;
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Controller\AbstractController', $controller);
    }

    public function testCacheMode()
    {
        $controller = new MyController;

        $controller->setCacheMode('transient');
        $controller->setCacheExpiresSeconds(60);

        $this->assertEquals('transient', $controller->getCacheMode());
        $this->assertEquals(60, $controller->getCacheExpiresSeconds());
    }

    public function testGetPost()
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPost')
            ->with(false, '\TimberPost')
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPost();
    }

    public function testGetPostForArgs()
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPost')
            ->with(['foo' => 'bar'], '\MyPostClass')
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPostForArgs(['foo' => 'bar'], '\MyPostClass');
    }

    public function testGetPosts()
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPosts')
            ->with(false, '\TimberPost', false)
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPosts();
    }

    public function testGetPostsForArgs()
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPosts')
            ->with(['foo' => 'bar'], '\MyPostClass', 'collection')
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPostsForArgs(['foo' => 'bar'], '\MyPostClass', 'collection');
    }

    public function testRender()
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getContext')
            ->andReturn([])
            ->shouldReceive('render')
            ->with([], [], false, 'default')
            ->mock();

        $MockBridge = new MockeryWpBridge;
        $MockBridge->mock()
            ->shouldReceive('addFilter')
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);
        $controller->setWpBridge($MockBridge);

        $controller->render();
    }
}
