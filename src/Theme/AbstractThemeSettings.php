<?php
namespace Gwa\Wordpress\Zero\Theme;

use Gwa\Wordpress\MockeryWpBridge\Traits\WpBridgeTrait;

/**
 * Extend this class make your theme settings are initialize theme modules.
 */
abstract class AbstractThemeSettings
{
    use WpBridgeTrait;

    /**
     * @var array
     */
    private $menus = [];

    /**
     * @var array
     */
    private $modules = [];

    /**
     * @var HookManager
     */
    private $hookmanager;

    final public function init()
    {
        $this->hookmanager = (new HookManager)->setWpBridge($this->getWpBridge());

        $this->doInit();
        $this->registerModules($this->getModuleClasses(), $this->hookmanager);

        $this->hookmanager->addFilter('timber_context', $this, 'addToContext');
    }

    /**
     * Override in concrete subclass!
     * - setViewsDirectory()
     * - addThemeLangSupport()
     * - registerMenus()
     * - addImageSize()
     */
    abstract protected function doInit();

    /**
     * Override in concrete subclass!
     *
     * @return array
     */
    protected function getModuleClasses()
    {
        return [];
    }

    /**
     * Override in concrete subclass!
     *
     * @return array
     */
    protected function getThemeContext()
    {
        return [];
    }

    /* ---------------- */

    final protected function setViewsDirectory($path)
    {
        \Timber::$locations = $path;
    }

    final private function registerModules(array $moduleclasses, HookManager $hookmanager)
    {
        foreach ($moduleclasses as $moduleclass) {
            $instance = new $moduleclass;
            $instance->init($this->getWpBridge(), $hookmanager);

            $this->modules[$moduleclass] = $instance;
        }
    }

    /**
     * @param string $slug
     * @param string $languagedirectory
     */
    final protected function addThemeLangSupport($slug, $languagedirectory = 'languages')
    {
        $this->getWpBridge()->loadThemeTextdomain($slug, get_template_directory() . '/' . $languagedirectory);
    }

    /**
     * @param array $data
     * @return array
     */
    final public function addToContext(array $data)
    {
        return array_merge(
            $data,
            $this->getMenuInstances(),
            $this->getModulesContext(),
            $this->getThemeContext()
        );
    }

    /**
     * @return array
     */
    private function getModulesContext()
    {
        $context = [];
        foreach ($this->modules as $module) {
            $context = array_merge($module->getContext(), $context);
        }
        return $context;
    }

    /**
     * @return array \TimberMenu instances
     */
    final protected function getMenuInstances()
    {
        $ret = [];
        foreach ($this->menus as $slug => $name) {
            $ret[] = new \TimberMenu($slug);
        }
        return $ret;
    }

    /**
     * @param string $name
     * @param integer $width
     * @param integer $height
     * @param boolean|array $crop
     */
    final protected function addImageSize($name, $width, $height, $crop = false)
    {
        $this->getWpBridge()->addImageSize($name, $width, $height, $crop);
    }

    /**
     * Config format:

     *     ['slug' => 'name', 'slug' => 'name']
     *
     * @param array $config
     */
    final protected function registerMenus($config)
    {
        $this->getWpBridge()->registerNavMenus($config);
    }

    /**
     * @return HookManager
     */
    final protected function getHookManager()
    {
        return $this->hookmanager;
    }
}
