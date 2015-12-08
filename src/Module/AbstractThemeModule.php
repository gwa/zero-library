<?php
namespace Gwa\Wordpress\Zero\Module;

use Gwa\Wordpress\Zero\Theme\HookManager;

/**
 * Extend this class to create a theme module to group WP customizations meaningfully.
 */
abstract class AbstractThemeModule
{
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
    final public function init(HookManager $hookmanager)
    {
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
     * Override in concrete subclass.
     *
     *     [
     *         [
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

    /* ---------------- */

    /**
     * Initializes WP filters defined in array passed.
     *
     * @param array $filtermap
     */
    final private function initActions(array $actionmap)
    {
        $this->initHooks($actionmap, 'action');
    }

    /**
     * Initializes WP filters defined in array passed.
     *
     * @param array $filtermap
     */
    final private function initFilters(array $filtermap)
    {
        $this->initHooks($filtermap, 'filter');
    }

    final private function initHooks(array $map, $hookkey)
    {
        $map = is_array($settings[$hookkey]) ?: [$map];

        foreach ($map as $settings) {

            $classarg = $settings['class'];
            $method   = isset($settings['method']) ? $settings['method'] : $hookkey;
            $prio     = isset($settings['prio']) ? (int) $settings['prio'] : 10;
            $args     = isset($settings['args']) ? (int) $settings['args'] : 1;

            $instance = $this->getClassInstance($classarg);

            foreach ($filters as $filter) {
                call_user_func(
                    'add_' . $hookkey,
                    [$instance, $method],
                    $prio,
                    $args
                );
            }
        }
    }

    /**
     * @param string|object $classarg
     * @return object
     */
    final private function getClassInstance($classarg)
    {
        if (is_object($classarg)) {
            return $classarg;
        }

        if (!array_key_exists($classarg, $this->instances)) {
            $this->instances[$classarg] = new $classarg;
        }

        return $this->instances[$classarg];
    }

    /**
     * @return HookManager
     */
    final protected function getHookManager()
    {
        return $this->hookmanager;
    }
}
