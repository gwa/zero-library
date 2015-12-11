<?php
namespace Gwa\Wordpress\Zero\Timber;

use Gwa\Wordpress\Zero\Timber\Contracts\TimberBridgeInterface;
use Mockery;

class MockeryTimberBridge implements TimberBridgeInterface
{
    /**
     * @var \Mockery
     */
    private $mock;

    /* -------- */

    public function __call($function, $args = [])
    {
        return call_user_func_array([$this->mock, $function], $args);
    }

    public function mock()
    {
        if (!isset($this->mock)) {
            $this->mock = Mockery::mock('TimberBridge');
        }

        return $this->mock;
    }
}
