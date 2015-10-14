<?php

namespace Gwa\Wordpress\Template\Zero\Library\Theme;

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
use TimberMenu;
use TimberSite;

/**
 * ThemeSettings.
 *
 * @author  GWA
 *
 */
class ThemeSettings extends TimberSite
{
    use WpBridgeTrait;

    /**
     * Add to context
     *
     * @param array $data
     *
     * @return array
     */
    public function addToContext($data)
    {
        $context = [
            'site' => $this,
        ];

        return array_merge($context, $data);
    }

    /**
     * WP_HEAD GOODNESS
     *
     * The default WordPress head is
     * a mess. Let's clean it up.
     */
    public function wpHeadCleanup()
    {
        // index link
        $this->getWpBridge()->removeAction('wp_head', 'index_rel_link');
        // previous link
        $this->getWpBridge()->removeAction('wp_head', 'parent_post_rel_link', 10, 0);
        // start link
        $this->getWpBridge()->removeAction('wp_head', 'start_post_rel_link', 10, 0);
        // remove WP version from css
        $this->getWpBridge()->addFilter('style_loader_src', [$this, 'removeWpVerCssJs'], 9999);
        // remove Wp version from scripts
        $this->getWpBridge()->addFilter('script_loader_src', [$this, 'removeWpVerCssJs'], 9999);
    }

    /**
     * Remove WP version from RSS
     */
    public function removeRssVersion()
    {
        return '';
    }

    /**
     * remove WP version from scripts
     */
    public function removeWpVerCssJs($src)
    {
        if (strpos($src, 'ver=')) {
            $src = $this->getWpBridge()->removeQueryArg('ver', $src);
        }

        return $src;
    }

    /**
     * Clean the output of attributes of images in editor.
     * Courtesy of SitePoint. http://www.sitepoint.com/wordpress-change-img-tag-html/
     *
     * @param string $class
     * @param string $id
     * @param string $align
     * @param string $size
     *
     * @return string
     */
    public function imageTagClassClean($class, $id, $align, $size)
    {
        $align = 'align'.esc_attr($align);
        return $align;
    }

    /**
     * Remove width and height in editor, for a better responsive world.
     *
     * @param string $html
     * @param string $id
     * @param string $alt
     * @param string $title
     *
     * @return string
     */
    public function imageEditorRemoveHightAndWidth($html, $id, $alt, $title)
    {
        return preg_replace([
                '/\s+width="\d+"/i',
                '/\s+height="\d+"/i',
                '/alt=""/i'
            ], [
                '',
                '',
                '',
                'alt="'.$title.'"'
            ], $html);
    }

    /**
     * Wrap images with figure tag.
     * Courtesy of Interconnectit http://interconnectit.com/2175/how-to-remove-p-tags-from-images-in-wordpress/
     *
     * @param string $content
     *
     * @return string
     */
    public function wrapImgInFigure($content)
    {
        $callback = function ($matches) {
            $img = $matches[1];
            $pattern = '/ class="([^"]+)"/';
            preg_match($pattern, $img, $imgClass);

            $class = '';

            if (isset($imgClass[1])) {
                $img   = preg_replace($pattern, '', $img);
                $class = ' class="' . $imgClass[1] . '"';
            }

            return '<figure' . $class . '>' . $img . '</figure>';
        };

        $content = preg_replace_callback('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', $callback, $content);

        return $content;
    }

    /**
     * Add id columns to column posts
     *
     * @param array $defaults
     */
    public function addColumnId($defaults)
    {
        $defaults['date column-id'] = __('ID');
        return $defaults;
    }

    /**
     * Add id columns to custom column post
     *
     * @param string $columnName
     * @param string $id
     */
    public function addColumnIdValue($columnName, $id)
    {
        if ($columnName === 'date column-id') {
            echo $id;
        }
    }

    /**
    * Return the ID for the column
    */
    public function addColumnReturnValue($value, $columName, $id)
    {
        if ($columName === 'date column-id') {
            $value = $id;
        }

        return $value;
    }

    /**
     * Remove image attributes
     *
     * @param  string $html
     *
     * @return string
     */
    public function removeImageAttributes($html)
    {
        $html = preg_replace('/(width|height)="\d*"\s/', '', $html);

        return $html;
    }

    /**
     * Adds a id column on all admin pages
     */
    public function addIdColumn()
    {
        foreach (get_taxonomies() as $taxonomy) {
            $this->getWpBridge()->addAction("manage_edit-${taxonomy}_columns", [$this, 'addColumnId']);
            $this->getWpBridge()->addFilter("manage_${taxonomy}_custom_column", [$this, 'addColumnReturnValue'], 10, 3);
            $this->getWpBridge()->addFilter("manage_edit-${taxonomy}_sortable_columns", [$this, 'addColumnId']);
        }

        foreach (get_post_types() as $ptype) {
            $this->getWpBridge()->addAction("manage_edit-${ptype}_columns", [$this, 'addColumnId']);
            $this->getWpBridge()->addFilter("manage_${ptype}_posts_custom_column", [$this, 'addColumnIdValue'], 10, 3);
            $this->getWpBridge()->addFilter("manage_edit-${ptype}_sortable_columns", [$this, 'addColumnId']);
        }

        $this->getWpBridge()->addAction('manage_media_custom_column', [$this, 'addColumnIdValue'], 10, 2);
        $this->getWpBridge()->addAction('manage_link_custom_column', [$this, 'addColumnId'], 10, 2);
        $this->getWpBridge()->addAction('manage_edit-link-categories_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addAction('manage_users_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addAction('manage_edit-comments_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addAction('manage_comments_custom_column', [$this, 'addColumnIdValue'], 10, 2);

        $this->getWpBridge()->addFilter('manage_media_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addFilter('manage_link-manager_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addFilter('manage_link_categories_custom_column', [$this, 'addColumnReturnValue'], 10, 3);
        $this->getWpBridge()->addFilter('manage_users_custom_column', [$this, 'addColumnReturnValue'], 10, 3);
        $this->getWpBridge()->addFilter('manage_edit-comments_sortable_columns', [$this, 'addColumnId']);
    }

    /**
     * Init
     */
    public function run()
    {
        global $wp_version;

        if (version_compare($wp_version, '4.2.1', '<')) {
            throw new \Exception('Your Wordpress version is too old, please upgrade to a newer version');
        }

        $this->getWpBridge()->addThemeSupport('post-formats', ['aside', 'image', 'link', 'quote', 'status']);
        $this->getWpBridge()->addThemeSupport('post-thumbnails');
        $this->getWpBridge()->addThemeSupport('menus');
        $this->getWpBridge()->addThemeSupport('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
        // This theme supports a variety of post formats.

        $this->getWpBridge()->addAction('init', [$this, 'wpHeadCleanup']);
        $this->getWpBridge()->addAction('admin_init', [$this, 'addIdColumn'], 199);

        (new TwigFilter())->init();

        $this->getWpBridge()->addFilter('the_generator', [$this, 'removeRssVersion']);
        $this->getWpBridge()->addFilter('get_image_tag_class', [$this, 'imageTagClassClean'], 0, 4);
        $this->getWpBridge()->addFilter('get_image_tag', [$this, 'imageEditorRemoveHightAndWidth'], 0, 4);
        $this->getWpBridge()->addFilter('the_content', [$this, 'wrapImgInFigure'], 30);
        $this->getWpBridge()->addFilter('post_thumbnail_html', [$this, 'removeImageAttributes'], 10);
        $this->getWpBridge()->addFilter('image_send_to_editor', [$this, 'removeImageAttributes'], 10);

        // Should be allways last.
        $this->getWpBridge()->addFilter('timber_context', [$this, 'addToContext']);
    }

    /**
     * Wordpress conditionals
     *
     * @return array
     */
    public function wpConditionals()
    {
        return [
            'is_home'              => $this->getWpBridge()->isHome(),
            'is_front_page'        => $this->getWpBridge()->isFrontPage(),
            'is_admin'             => $this->getWpBridge()->isAdmin(),
            'is_single'            => $this->getWpBridge()->isSingle(),
            'is_sticky'            => $this->getWpBridge()->isSticky(),
            'get_post_type'        => $this->getWpBridge()->getPostType(),
            'is_single'            => $this->getWpBridge()->isSingle(),
            'is_post_type_archive' => $this->getWpBridge()->isPostTypeArchive(),
            'is_page'              => $this->getWpBridge()->isPage(),
            'is_page_template'     => $this->getWpBridge()->isPageTemplate(),
            'is_category'          => $this->getWpBridge()->isCategory(),
            'is_tag'               => $this->getWpBridge()->isTag(),
            'has_tag'              => $this->getWpBridge()->hasTag(),
            'is_tax'               => $this->getWpBridge()->isTax(),
            'has_term'             => $this->getWpBridge()->hasTerm(),
            'is_author'            => $this->getWpBridge()->isAuthor(),
            'is_date'              => $this->getWpBridge()->isDate(),
            'is_year'              => $this->getWpBridge()->isYear(),
            'is_month'             => $this->getWpBridge()->isMonth(),
            'is_day'               => $this->getWpBridge()->isDay(),
            'is_time'              => $this->getWpBridge()->isTime(),
            'is_archive'           => $this->getWpBridge()->isArchive(),
            'is_search'            => $this->getWpBridge()->isSearch(),
            'is_404'               => $this->getWpBridge()->is404(),
            'is_paged'             => $this->getWpBridge()->isPaged(),
            'is_attachment'        => $this->getWpBridge()->isAttachment(),
            'is_singular'          => $this->getWpBridge()->isSingular(),
            'template_uri'         => $this->getWpBridge()->getTemplateDirectoryUri(),
            'single_cat_title'     => $this->getWpBridge()->singleCatTitle('', false),
        ];
    }

    /**
     * Initializes WP filters defined in $filtermap.
     *
     * @param array $filtermap
     */
    protected function initFilters(array $filtermap)
    {
        $map = $this->getNormalizedFilterMap($filtermap);

        foreach ($map['hooks'] as $event) {
            $this->getWpBridge()->addFilter($event, [$map['instance'], $map['method']], $map['prio'], $map['args']);
        }
    }

    /**
     * Initializes WP filters defined in $filtermap.
     *
     * @param array $filtermap
     */
    protected function initActions(array $filtermap)
    {
        $map = $this->getNormalizedFilterMap($filtermap);

        foreach ($map['hooks'] as $event) {
            $this->getWpBridge()->addAction($event, [$map['instance'], $map['method']], $map['prio'], $map['args']);
        }
    }

    protected function getNormalizedFilterMap(array $filtermap)
    {
        $arr = [];

        foreach ($filtermap as $settings) {
            $hooks    = is_array($settings['hooks']) ?: [$settings['hooks']];
            $class    = ($settings['class'] instanceof self) ? $settings['class'] : new $settings['class'];

            $method   = isset($settings['method']) ?: 'hooks';
            $prio     = isset($settings['prio']) ?: 10;
            $args     = isset($settings['args']) ?: 1;

            if ($class instanceof self) {
                $instance = $class;
            } else {
                $instance = new $class;
            }

            $arr = [
                'hooks'    => $hooks,
                'instance' => $class,
                'method'   => $method,
                'prio'     => (int) $prio,
                'args'     => (int) $args,
            ]
        }

        return $arr;
    }
}
