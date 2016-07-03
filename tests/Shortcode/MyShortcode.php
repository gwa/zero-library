<?php
namespace Gwa\Wordpress\Zero\Test\Shortcode;

use Gwa\Wordpress\Zero\Shortcode\AbstractShortcode;
use Gwa\Wordpress\Zero\Shortcode\Traits\GetIdsArrayTrait;

class MyShortcode extends AbstractShortcode
{
    use GetIdsArrayTrait;

    /**
     * @return string
     */
    public function getShortcode()
    {
        return 'myshortcode';
    }

    /**
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function render($atts, $content = '')
    {
        $atts = $this->getNormedAtts($atts);
        return $atts['foo'];
    }

    /**
     * Method to test GetIdsArrayTrait
     * @param $ids string
     */
    public function getIds($ids)
    {
        return $this->getIdsArray($ids);
    }
}
