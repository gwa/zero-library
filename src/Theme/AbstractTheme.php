<?php
namespace Gwa\Wordpress\Zero\Theme;

use Gwa\Wordpress\MockeryWpBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\Zero\Theme\MenuFactory\MenuFactoryContract;
use Gwa\Wordpress\Zero\Theme\MenuFactory\TimberMenuFactory;

/**
 * Extend this class make your theme settings are initialize theme modules.
 */
abstract class AbstractTheme
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

        $this->getHookManager()->addFilter('timber_context', $this, 'addToContext');
    }

    /**
     * Override in concrete subclass!
     * Do stuff like this:
     *
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

    /**
     * Sets the absolute path to the directory containing the twig files.
     *
     * @param string $path
     * @codeCoverageIgnore
     */
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
        $this->getWpBridge()->loadThemeTextdomain(
            $slug,
            $this->getWpBridge()->getTemplateDirectory() . '/' . $languagedirectory
        );
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
     * @return array Menu instances to be passed to the view context.
     */
    final protected function getMenuInstances()
    {
        $ret = [];
        foreach ($this->menus as $slug => $name) {
            $ret['menu_' . $slug] = $this->getMenuFactory()->create($slug);
        }
        return $ret;
    }

    /**
     * Register a WP image size.
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
     *
     *     ['slug' => 'name', 'slug2' => 'name2']
     *
     * @param array $config
     */
    final protected function registerMenus($config)
    {
        $this->menus = $config;
        $this->getWpBridge()->registerNavMenus($config);
    }

    /**
     * Creates a controller (typically from a WP theme PHP file).
     *
     * @param string $classname
     * @return \Gwa\Wordpress\Zero\Controller\AbstractController
     */
    public function createController($classname)
    {
        return (new $classname())
            ->setTheme($this)
            ->setWpBridge($this->getWpBridge());
    }

    /**
     * @return HookManager
     */
    final protected function getHookManager()
    {
        return $this->hookmanager;
    }

    /**
     * @return MenuFactoryContract
     */
    final public function getMenuFactory()
    {
        if (!isset($this->menufactory)) {
            $this->menufactory = new TimberMenuFactory;
        }

        return $this->menufactory;
    }

    /**
     * @param MenuFactoryContract $factory
     */
    final public function setMenuFactory(MenuFactoryContract $factory)
    {
        $this->menufactory = $factory;
    }
}
