<?php
namespace Gwa\Wordpress\Zero\Test\Post;

class AbstractCustomPostTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $instance = new MyCustomPostType;
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Post\AbstractCustomPostType', $instance);
    }
}
