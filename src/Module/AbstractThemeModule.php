<?php
namespace Gwa\Wordpress\Zero\Module;

use Gwa\Wordpress\MockeryWpBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\Zero\Theme\HookManager;

/**
 * Extend this class to create a theme module to group WP customizations meaningfully.
 */
abstract class AbstractThemeModule
{
    use WpBridgeTrait;

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var HookManager
     */
    private $hookmanager;

    /**
     * @param HookManager $hookmanager
     */
    final public function init($bridge, HookManager $hookmanager)
    {
        $this->setWpBridge($bridge);
        $this->hookmanager = $hookmanager;

        $this->doInit();

        $this->hookmanager->addActions($this->getActionMap());
        $this->hookmanager->addFilters($this->getFilterMap());
    }

    /* ---------------- */

    protected function doInit()
    {
        // Override in subclass, if required
    }

    /**
     * @return array
     */
    public function getContext()
    {
        // Override in subclass, if required
        return [];
    }

    /**
     * Override in concrete subclass.
     *
     *     [
     *         [
     *             'hooks'  => 'hookname', // or an array of hooks
     *             'class'  => '\My\Filter\Class', // or an instance
     *             'method' => 'action',
     *             'prio'   => 10,
     *             'args'   => 1, // no. of args method accepts
     *         ]
     *     ]
     *
     * @return array
     */
    protected function getActionMap()
    {
        return [];
    }

    /**
     * Override in concrete subclass.
     *
     * @return array
     */
    protected function getFilterMap()
    {
        return [];
    }

    /**
     * @return HookManager
     */
    final protected function getHookManager()
    {
        return $this->hookmanager;
    }
}
