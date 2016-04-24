<?php
namespace Gwa\Wordpress\Zero\Traits;

use Gwa\Wordpress\Zero\Theme\AbstractTheme;

/**
 * Getter/setter methods for classes with access to the Theme.
 */
trait HasTheme
{
    /**
     * @var AbstractTheme
     */
    private $theme;

    /**
     * @return AbstractTheme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param AbstractTheme $theme
     */
    public function setTheme(AbstractTheme $theme)
    {
        $this->theme = $theme;
        return $this;
    }
}
