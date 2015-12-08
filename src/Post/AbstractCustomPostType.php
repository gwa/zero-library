<?php
namespace Gwa\Wordpress\Zero\Post;

use CPT;

abstract class AbstractCustomPostType
{
    /**
     * @var string
     */
    protected $textdomain;

    /**
     * @param string $textdomain
     */
    final public function init($textdomain)
    {
        $this->textdomain = $textdomain;

        $post = $this->createPostType();

        if ($settings = $this->getTaxonomySettings()) {
            $post->register_taxonomy($settings);
        }

        $this->addExtra();
    }

    /**
     * @return CPT
     */
    public function createPostType()
    {
        $options = array_merge(
            [
                'supports' => $this->getSupports()
            ],
            $this->getOptions()
        );

        $post = new CPT(
            [
                'post_type_name' => $this->getPostType(),
                'singular'       => $this->getSingular(),
                'plural'         => $this->getPlural(),
                'slug'           => $this->getSlug(),
            ],
            $options
        );

        $post->menu_icon($this->getIcon());
        $post->set_textdomain($this->getTextDomain());

        return $post;
    }

    /* -------- ABSTRACT METHODS -------- */

    /**
     * @return string
     */
    abstract public function getPostType();

    /**
     * @return string
     */
    abstract public function getSingular();

    /**
     * @return string
     */
    abstract public function getPlural();

    /**
     * @return string
     * @link https://developer.wordpress.org/resource/dashicons/
     */
    abstract public function getIcon();

    /* -------- OVERRIDE METHODS --------- */

    /**
     * Defaults to slug.
     * @return string
     */
    public function getSlug()
    {
        return $this->getPostType();
    }

    /**
     * @return string
     */
    public function getTextDomain()
    {
        return $this->textdomain;
    }

    /**
     * @return string[]
     */
    public function getSupports()
    {
        return [
            'title',
            'editor',
            'thumbnail',
            'page-attributes',
        ];
    }

    /**
     * @return string[]
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return array|null
     */
    public function getTaxonomySettings()
    {
        return null;
    }

    public function addExtra()
    {
        // hook for subclass
    }

    /* -------- CUSTOM FIELDS --------- */

    /**
     * @param string $title
     * @param string $slug
     * @param string $context
     * @param string $priority
     * @param boolean $shownames
     * @return \CMB2
     * @link https://github.com/WebDevStudios/CMB2
     */
    final protected function addMetaBox(
        $title = 'Custom data',
        $slug = 'custom_meta',
        $context = 'normal',
        $priority = 'default',
        $shownames = true
    ) {
        return new_cmb2_box([
            'id'            => $this->getPostType() . '_' . $slug,
            'title'         => $title,
            'object_types'  => [$this->getPostType()],
            'context'       => $context,
            'priority'      => $priority,
            'show_names'    => $shownames,
        ]);
    }

    final protected function addFieldToBox($box, $type, $slug, $name, $description = '', $atts = [])
    {
        $defaults = [
            'name'    => $name,
            'desc'    => $description,
            'id'      => $this->getIdForSlug($slug),
            'type'    => $type
        ];

        $atts = array_merge($defaults, $atts);

        $box->add_field($atts);
    }

    /**
     * @param string $slug
     * @return string
     */
    final protected function getIdForSlug($slug)
    {
        return $this->getPostType() . '_' . $slug;
    }
}
