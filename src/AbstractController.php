<?php

namespace Gwa\Wordpress\Template\Zero\Library;

/**
 * Zero Library.
 *
 * @author      Daniel Bannert <bannert@greatwhiteark.com>
 * @copyright   2015 Great White Ark
 *
 * @link        http://www.greatwhiteark.com
 *
 * @license     MIT
 */

use Gwa\Wordpress\MockeryWpBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\Template\Core\WPBridge\WPBridge;
use LogicException;
use RuntimeException;
use Timber;
use TimberLoader;
use WP_Query;

/**
 * AbstractController.
 *
 * @author  GWA
 *
 */
abstract class AbstractController
{
    use WpBridgeTrait;

    protected $cacheType = [
        'none'           => TimberLoader::CACHE_NONE,
        'object'         => TimberLoader::CACHE_OBJECT,
        'transient'      => TimberLoader::CACHE_TRANSIENT,
        'site.transient' => TimberLoader::CACHE_SITE_TRANSIENT,
        'default'        => TimberLoader::CACHE_USE_DEFAULT,
    ];

    /**
     * Cache expires time
     *
     * @var int
     */
    protected $cacheExpiresSecond;

    /**
     * Cache mode.
     *
     * @var string
     */
    protected $cacheMode = TimberLoader::CACHE_USE_DEFAULT;

    /**
     * WP_Query instance.
     *
     * @var \WP_Query
     */
    protected $wpQuery;

    /**
     * AbstractController instance.
     */
    public function __construct()
    {
        if (!class_exists('TimberLoader')) {
            throw new RuntimeException(
                'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>'
            );
        }

        $this->setWPBridge(new WPBridge());
    }

    /**
     * Set \Wp_Query args
     */
    public function setWpQuery(array $args)
    {
        $this->wpQuery = new WP_Query($args);
    }

    /**
     * Get Wp_Query
     *
     * @return \WP_Query
     */
    public function getWpQuery()
    {
        global $wp_query;
        return isset($this->wpQuery) ? $this->wpQuery : $wp_query;
    }

    /**
     * Set cache mode
     *
     * @param string  $mode
     *
     * @return self
     */
    public function setCacheMode($mode = 'default')
    {
        $this->cacheMode = $this->cacheType[$mode];

        return $this;
    }

    /**
     * Get cache mode
     *
     * @return string
     */
    public function getCacheMode()
    {
        return $this->cacheType[$this->cacheMode];
    }

    /**
     * Set cache expires seconds
     *
     * Timber will cache the template for 10 minutes (600 / 60 = 10).
     *
     * @param boolean $second
     *
     * @return self
     */
    public function setCacheExpiresSecond($second)
    {
        $this->cacheExpiresSecond = $second;

        return $this;
    }

    /**
     * Get cache expires seconds
     *
     * @return integer
     */
    public function getCacheExpiresSecond()
    {
        return $this->cacheExpiresSecond;
    }

    /**
     * Get context
     *
     * @return array<string,\Timber|string>|null|array
     */
    abstract public function getContext();

    /**
     * Get template
     *
     * @return string[]
     */
    abstract public function getTemplates();

    /**
     * Get Post
     *
     * @param string $postClass
     *
     * @return array|boolean|null
     */
    public function getPost($postClass = '\TimberPost')
    {
        return Timber::get_post(false, $postClass);
    }

    /**
     * Get Post on some parameters
     *
     * @param string[] $args
     * @param string   $postClass
     *
     * @return array|boolean|null
     */
    public function getPostForArgs($args, $postClass = '\TimberPost')
    {
        return Timber::get_post($args, $postClass);
    }

    /**
     * Get Posts
     *
     * @param string  $postClass
     * @param boolean $collection
     *
     * @return array|boolean|null
     */
    public function getPosts($postClass = '\TimberPost', $collection = false)
    {
        return Timber::get_posts(false, $postClass, $collection);
    }

    /**
     * Get Posts on some parameters
     *
     * @param string[] $args
     * @param string   $postClass
     * @param boolean  $collection
     *
     * @return array|boolean|null
     */
    public function getPostsForArgs($args, $postClass = '\TimberPost', $collection = false)
    {
        return Timber::get_posts($args, $postClass, $collection);
    }

    /**
     *  Render template
     *
     * @return boolean|string|null
     */
    public function render()
    {
        $context = $this->getContext();

        $this->validateTemplates($this->getTemplates());
        $this->validateContext($context);

        Timber::render(
            $this->getTemplates(),
            array_merge(Timber::get_context(), $context),
            // False disables cache altogether.
            ($this->getCacheExpiresSecond() ?: false),
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

        foreach ($templates as $template) {
            if (!is_file(get_template_directory().'/views/'.$template) && !is_file(get_template_directory().'/views/'.end($templates))) {
                throw new LogicException(sprintf('Template [%s] dont exists.', $template));
            }
        }
    }
}
