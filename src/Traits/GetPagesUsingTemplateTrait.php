<?php
namespace Gwa\Wordpress\Template\ContentHub\Post\Timber\Traits;

/**
 * Add methods for returning pages using a template.
 * Useful
 *
 */
trait GetPagesUsingTemplateTrait
{
    /**
     * @param string $templatefile Full template filename, including `.php`.
     *
     * @return array
     */
    public function getPagesUsingTemplate($templatefile)
    {
        return get_pages([
            'meta_key'   => '_wp_page_template',
            'meta_value' => $templatefile
        ]);
    }

    /**
     * @param string $templatefile Full template filename, including `.php`.
     *
     * @return \stdClass|null
     */
    public function getPageUsingTemplate($templatefile)
    {
        $pages = $this->getPagesUsingTemplate($templatefile);

        return count($pages) ? $pages[0] : null;
    }
}
