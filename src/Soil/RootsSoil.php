<?php
namespace Gwa\Wordpress\Zero\Soil;

/**
 *
 */
class RootsSoil
{
    /**
     * All soil theme func
     *
     * @var array
     */
    protected $soilFunc = [
        'soil-jquery-cdn'               => false,
        'soil-clean-up'                 => true,
        'soil-nav-walker'               => true,
        'soil-relative-urls'            => true,
        'soil-js-to-footer'             => true,
        'soil-disable-trackbacks'       => true,
        'soil-disable-asset-versioning' => true,
    ];

    protected $google = [
        'boot' => false,
        'user' => '',
    ];

    /**
     * Change the standard configs
     *
     * @param  array $soil
     *
     * @return self
     */
    public function changeOptions(array $soil)
    {
        $this->soilFunc = array_merge($this->soilFunc, $soil);

        return $this;
    }

    /**
     * Add google analytics
     *
     * @param bool   $boot
     * @param string $user
     */
    public function addGoogleAnalytics($boot = false, $user = '')
    {
        $this->google['boot'] = $boot;
        $this->google['user'] = $user;
    }

    /**
     * Active soil thme supports.
     */
    public function init()
    {
        foreach ($this->soilFunc as $key => $value) {
            if (is_bool($value) && $value === true) {
                add_theme_support($key);
            }
        }

        $google = $this->google;

        if (is_bool($google['boot']) && $google['boot'] === true) {
            add_theme_support('soil-google-analytics', $google['user']);
        }
    }
}
