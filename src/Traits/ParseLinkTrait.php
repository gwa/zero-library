<?php

namespace Gwa\Wordpress\Template\Zero\Library\Traits;

/**
 * Zero Library.
 *
 * @author      Daniel Bannert <bannert@greatwhiteark.com>
 * @copyright   2015 Great White Ark
 *
 * @link        http://www.greatwhiteark.com
 *
 * @license     MIT
 */

use Gwa\Wordpress\MockeryWpBridge\Traits\WpBridgeTrait;

/**
 * ParseLinkTrait.
 *
 * @author  GWA
 *
 */
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
