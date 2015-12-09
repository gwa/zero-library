<?php
namespace Gwa\Wordpress\Zero\Post;

use Gwa\Wordpress\Zero\Traits\AddCustomControl;

abstract class AbstractCustomPostType
{
    use AddCustomControl;

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
     * @return \CPT
     */
    public function createPostType()
    {
        $options = array_merge(
            [
                'supports' => $this->getSupports()
            ],
            $this->getOptions()
        );

        $post = new \CPT(
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
}
