<?php
namespace Gwa\Wordpress\Zero\Controller\Traits;

trait ParseLinkTrait
{
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

    abstract public function getWpBridge();
}
