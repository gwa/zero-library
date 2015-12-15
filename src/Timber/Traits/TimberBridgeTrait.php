<?php
namespace Gwa\Wordpress\Zero\Timber\Traits;

use Gwa\Wordpress\Zero\Timber\Contracts\TimberBridgeInterface;

trait TimberBridgeTrait
{
    /**
     * @var TimberBridgeInterface
     */
    private $timberbridge;

    /**
     * @return TimberBridgeInterface
     */
    public function getTimberBridge()
    {
        return $this->timberbridge;
    }

    /**
     * @param TimberBridgeInterface $bridge
     */
    public function setTimberBridge(TimberBridgeInterface $bridge)
    {
        $this->timberbridge = $bridge;
        return $this;
    }
}
