<?php
namespace Gwa\Wordpress\Zero\Test\Shortcode;

use Gwa\Wordpress\Zero\Shortcode\AbstractShortcode;

class MyShortcode extends AbstractShortcode
{
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
}
