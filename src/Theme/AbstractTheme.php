<?php
namespace Gwa\Wordpress\Zero\Theme;

use Gwa\Wordpress\WpBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\WpBridge\WpBridge;
use Gwa\Wordpress\Zero\Theme\MenuFactory\MenuFactoryContract;
use Gwa\Wordpress\Zero\Theme\MenuFactory\TimberMenuFactory;
use Gwa\Wordpress\Zero\Timber\TimberBridge;
use Gwa\Wordpress\Zero\Timber\Traits\TimberBridgeTrait;
use Timber\Timber;

/**
 * Extend this class make your theme settings are initialize theme modules.
 */
abstract class AbstractTheme
{
    use TimberBridgeTrait;
    use WpBridgeTrait;

    /**
     * @var string $environment
     */
    private $environment;

    /**
     * @var array $menus
     */
    private $menus = [];

    /**
     * @var array $modules
     */
    private $modules = [];

    /**
     * Set in concrete Theme class to activate language support.
     * @var string $textdomain
     */
    protected $textdomain;

    /**
     * @var HookManager $hookmanager
     */
    private $hookmanager;

    /**
     * @param string $environment
     */
    final public function __construct($environment = 'production')
    {
        $this->environment = $environment;

        // set default WpBridge
        $this->setWpBridge(new WpBridge);

        // set default TimberBridge
        $this->setTimberBridge(new TimberBridge);
    }

    final public function init()
    {
        $this->hookmanager = (new HookManager)->setWpBridge($this->getWpBridge());

        $this->doInit();
        $this->registerModules($this->getModuleClasses(), $this->hookmanager);

        $this->getHookManager()->addFilter('timber_context', $this, 'addToContext');

        // Add language support if textdomain is set
        if (isset($this->textdomain)) {
            $this->getHookManager()->addAction('after_setup_theme', $this, 'addThemeLangSupport');
        }

        // Prevent indexing on non-production environments
        if ($this->environment !== 'production') {
            $this->getWpBridge()->addFilter('pre_option_blog_public', '__return_false');
        }
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
        Timber::$locations = $path;
    }

    final private function registerModules(array $moduleclasses, HookManager $hookmanager)
    {
        foreach ($moduleclasses as $moduleclass) {
            $instance = new $moduleclass;
            $instance->init($this->getWpBridge(), $hookmanager);
            $instance->setTheme($this);

            $this->modules[$moduleclass] = $instance;
        }
    }

    /**
     */
    final public function addThemeLangSupport()
    {
        $this->getWpBridge()->loadThemeTextdomain(
            $this->textdomain,
            $this->getWpBridge()->getTemplateDirectory() . '/languages'
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
            ->setTimberBridge($this->getTimberBridge())
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

    /**
     * @return string
     */
    final public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return string|null
     */
    final public function getTextDomain()
    {
        return $this->textdomain;
    }

    /**
     * Convenience method to allow modules and controllers to get translated text from the theme text domain.
     *
     * @param string $text
     * @return string
     */
    final public function __($text)
    {
        return $this->getWpBridge()->__($text, $this->getTextDomain());
    }
}
