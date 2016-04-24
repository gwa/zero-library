<?php
namespace Gwa\Wordpress\Zero\Controller;

use Gwa\Wordpress\WpBridge\Contracts\WpBridgeAwareInterface;
use Gwa\Wordpress\WpBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\Zero\Theme\AbstractTheme;
use Gwa\Wordpress\Zero\Timber\Traits\TimberBridgeTrait;
use Gwa\Wordpress\Zero\Traits\HasTheme;
use LogicException;
use TimberLoader;

abstract class AbstractController
{
    use TimberBridgeTrait;
    use WpBridgeTrait;
    use HasTheme;

    protected $cacheType = [
        'none'           => TimberLoader::CACHE_NONE,
        'object'         => TimberLoader::CACHE_OBJECT,
        'transient'      => TimberLoader::CACHE_TRANSIENT,
        'site.transient' => TimberLoader::CACHE_SITE_TRANSIENT,
        'default'        => TimberLoader::CACHE_USE_DEFAULT,
    ];

    /**
     * @var int
     */
    protected $cacheExpiresSeconds;

    /**
     * @var string
     */
    protected $cacheMode = TimberLoader::CACHE_USE_DEFAULT;

    /**
     * @param string  $mode
     * @return self
     */
    public function setCacheMode($mode = 'default')
    {
        $this->cacheMode = $this->cacheType[$mode];

        return $this;
    }

    /**
     * @return string
     */
    public function getCacheMode()
    {
        return $this->cacheType[$this->cacheMode];
    }

    /**
     * @param integer $seconds
     * @return self
     */
    public function setCacheExpiresSeconds($seconds)
    {
        $this->cacheExpiresSeconds = $seconds;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCacheExpiresSeconds()
    {
        return $this->cacheExpiresSeconds;
    }

    /**
     * @return array<string,\Timber|string>|null|array
     */
    abstract public function getContext();

    /**
     * @return string[]
     */
    abstract public function getTemplates();

    /**
     * @param string $postClass
     * @return array|boolean|null
     */
    public function getPost($postClass = '\TimberPost')
    {
        return $this->getPostForArgs(false, $postClass);
    }

    /**
     * @param string[] $args
     * @param string   $postClass
     *
     * @return array|boolean|null
     */
    public function getPostForArgs($args, $postClass = '\TimberPost')
    {
        return $this->getTimberBridge()->getPost($args, $postClass);
    }

    /**
     * @param string  $postClass
     * @param boolean $collection
     *
     * @return array|boolean|null
     */
    public function getPosts($postClass = '\TimberPost', $collection = false)
    {
        return $this->getPostsForArgs(false, $postClass, $collection);
    }

    /**
     * @param string[] $args
     * @param string   $postClass
     * @param boolean  $collection
     *
     * @return array|boolean|null
     */
    public function getPostsForArgs($args, $postClass = '\TimberPost', $collection = false)
    {
        return $this->getTimberBridge()->getPosts($args, $postClass, $collection);
    }

    /**
     *  Render template
     *
     * @return boolean|string|null
     */
    public function render()
    {
        $this->getWpBridge()->addFilter('timber_post_getter_get_posts', [$this, 'addWpBridgeToPosts'], 10, 3);

        $context   = $this->getContext();
        $templates = $this->getTemplates();

        $this->validateTemplates($templates);
        $this->validateContext($context);

        $this->getTimberBridge()->render(
            $templates,
            array_merge($this->getTimberBridge()->getContext(), $context),
            ($this->getCacheExpiresSeconds() ?: false), // False disables cache altogether.
            $this->getCacheMode()
        );
    }

    /**
     * Check if context is a array
     *
     * @param array|null $context
     */
    protected function validateContext($context)
    {
        if (!is_array($context)) {
            throw new LogicException('::getContext should return a array');
        }
    }

    /**
     * Check if getTemplates is a array and template file exist
     *
     * @param string[] $templates
     */
    protected function validateTemplates($templates)
    {
        if (!is_array($templates)) {
            throw new LogicException('::getTemplates should return a array');
        }
    }

    /**
     * Returns basename of template set for current page.
     * Useful for setting templates based on template slug.
     *
     * @return string
     */
    protected function getTemplateSlug()
    {
        return $this->getWpBridge()->getPageTemplateSlug();
    }

    public function addWpBridgeToPosts($posts)
    {
        foreach ($posts as $key => $post) {
            if ($post instanceof WpBridgeAwareInterface) {
                $posts[$key] = $post->setWpBridge($this->getWpBridge());
            }
        }

        return $posts;
    }
}
