<?php
namespace Gwa\Wordpress\Zero\Traits;

/**
 * Trait that abstracts CMB2.
 * Add trait to a module to add control boxes and controls.
 */
trait AddCustomControl
{
    /**
     * @param string|array $posttype
     * @param string $slug
     * @param string $title
     * @param string $context
     * @param string $priority
     * @param boolean $shownames
     * @return \CMB2
     * @link https://github.com/WebDevStudios/CMB2
     */
    protected function createControlBox($posttype, $slug = 'custom_meta', $title = 'Custom data', $context = 'normal', $priority = 'default', $shownames = true)
    {
        if (!is_array($posttype)) {
            $posttype = [$posttype];
        }

        return new_cmb2_box([
            'id'            => $slug,
            'title'         => $title,
            'object_types'  => $posttype,
            'context'       => $context,
            'priority'      => $priority,
            'show_names'    => $shownames,
        ]);
    }

    /**
     * @param \CMB2 $box
     * @param string $type Type of field
     * @param string $slug
     * @param string $name
     * @param string $description
     * @param array $atts
     * @link https://github.com/WebDevStudios/CMB2
     */
    protected function addFieldToBox($box, $type, $slug, $name, $description = '', $atts = [])
    {
        $defaults = [
            'name'    => $name,
            'desc'    => $description,
            'id'      => $slug,
            'type'    => $type
        ];

        $atts = array_merge($defaults, $atts);

        $box->add_field($atts);
    }
}
