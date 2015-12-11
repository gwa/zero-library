<?php
namespace Gwa\Wordpress\Zero\Shortcode\Contract;

interface TemplateRendererInterface
{
    public function render($name, array $context = []);
}
