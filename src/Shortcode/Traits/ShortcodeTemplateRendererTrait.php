<?php
namespace Gwa\Wordpress\Zero\Shortcode\Traits;

use Gwa\Wordpress\Zero\Shortcode\Contract\ShortcodeTemplateRendererInterface;

/**
 * Trait to be used by all shortcodes that use a renderer for rendering.
 * Concrete renderer class needs to be set before renderering.
 */
trait ShortcodeTemplateRendererTrait
{
    /**
     * @var ShortcodeTemplateRendererInterface $renderer
     */
    private $renderer;

    /**
     * @param ShortcodeTemplateRendererInterface $rendere
     * r
     * @return ShortcodeTemplateRendererTrait
     */
    public function setTemplateRenderer(ShortcodeTemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @return ShortcodeTemplateRendererInterface
     */
    public function getTemplateRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param string $template
     * @param array  $data
     */
    protected function renderTemplate($template, $data)
    {
        return $this->getTemplateRenderer()->render($template, $data);
    }
}
