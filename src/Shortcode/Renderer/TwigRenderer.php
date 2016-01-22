<?php
namespace Gwa\Wordpress\Zero\Shortcode\Renderer;

use Gwa\Wordpress\Zero\Shortcode\Contract\TemplateRendererInterface;

/**
 * Extend Twig_Environment to implement TemplateRendererInterface.
 * Required methods already exist on Twig_Environment.
 */
class TwigRenderer extends \Twig_Environment implements TemplateRendererInterface
{
}
