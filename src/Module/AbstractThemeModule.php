<?php
namespace Gwa\Wordpress\Zero\Module;

use Gwa\Wordpress\WpBridge\Contracts\WpBridgeInterface;
use Gwa\Wordpress\WpBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\Zero\Theme\HookManager;
use Gwa\Wordpress\Zero\Traits\HasTheme;

/**
 * Extend this class to create a theme module to group WP customizations meaningfully.
 */
abstract class AbstractThemeModule
{
    use WpBridgeTrait, HasTheme;

    /**
     * @var HookManager
     */
    private $hookmanager;

    /**
     * Each module should have a unique slug.
     * @var string $slug
     */
    protected $slug;

    /**
     * @param WpBridgeInterface $bridge
     * @param HookManager $hookmanager
     */
    final public function init(WpBridgeInterface $bridge, HookManager $hookmanager)
    {
        $this->setWpBridge($bridge);
        $this->hookmanager = $hookmanager;

        $this->doInit();

        $this->registerShortcodes($this->getShortcodeClasses());

        $this->getHookManager()->addActions($this->getActionMap());
        $this->getHookManager()->addFilters($this->getFilterMap());
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
     * Override in concrete subclass!
     *
     * @return array
     */
    protected function getShortcodeClasses()
    {
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

    /* ---------------- */

    final private function registerShortcodes(array $shortcodeclasses)
    {
        foreach ($shortcodeclasses as $shortcodeclass) {
            $instance = new $shortcodeclass;
            $instance->init($this->getWpBridge(), $this);
        }
    }

    /**
     * @return HookManager
     */
    final protected function getHookManager()
    {
        return $this->hookmanager;
    }

    /**
     * @return string|null
     */
    final public function getSlug()
    {
        return $this->slug;
    }
}
