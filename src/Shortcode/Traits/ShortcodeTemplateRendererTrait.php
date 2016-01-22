<?php
namespace Gwa\Wordpress\Zero\Shortcode\Traits;

use Gwa\Wordpress\Zero\Shortcode\Contract\TemplateRendererInterface;

/**
 * Trait to be used by all shortcodes that use a template renderer for rendering.
 * Concrete renderer class needs to be set before renderering.
 */
trait ShortcodeTemplateRendererTrait
{
    /**
     * @var TemplateRendererInterface $renderer
     */
    private $renderer;

    /**
     * Call setTemplateRenderer() from doInit().
     *
     * @param TemplateRendererInterface $renderer
     * @return ShortcodeTemplateRendererTrait
     */
    public function setTemplateRenderer(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @return TemplateRendererInterface
     */
    public function getTemplateRenderer()
    {
        return $this->renderer;
    }

    /**
     * Implement a render() method, that then calls renderTemplate()
     *
     * @param string $template
     * @param array  $data
     */
    protected function renderTemplate($template, $data)
    {
        return $this->getTemplateRenderer()->render($template, $data);
    }
}
