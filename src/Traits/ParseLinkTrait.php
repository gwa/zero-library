<?php
namespace Gwa\Wordpress\Template\Zero\Library\Traits;

use Gwa\Wordpress\MockeryWpBridge\Traits\WpBridgeTrait;

trait ParseLinkTrait
{
    use WpBridgeTrait;

    /**
     * Link can be a URL, or an post ID.
     *
     * @param string $link
     * @return string
     */
    protected function parseLink($link)
    {
        if (is_numeric($link)) {
            return $this->getWpBridge()->getPermalink($idpost);
        }

        return $link;
    }
}
