<?php
namespace Gwa\Wordpress\Zero\Timber\Contracts;

interface TimberBridgeInterface
{
    public function __call($function, $args = []);
}
