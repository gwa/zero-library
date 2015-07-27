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

use Gwa\Wordpress\MockeryWPBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\MockeryWPBridge\Contracts\WPBridgeInterface;
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
     * Lets set wpbridge.
     *
     * @param WPBridgeInterface $wpbridge
     */
    public function __construct(WPBridgeInterface $wpbridge)
    {
        $this->setWPBridge($wpbridge);
    }

    /**
     * Add to context
     *
     * @param array $data
     *
     * @return array
     */
    public function addToContext($data)
    {
        return array_merge(['site' => $this], $data);
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
        $this->getWPBridge()->removeAction('wp_head', 'index_rel_link');
        // previous link
        $this->getWPBridge()->removeAction('wp_head', 'parent_post_rel_link', 10, 0);
        // start link
        $this->getWPBridge()->removeAction('wp_head', 'start_post_rel_link', 10, 0);
        // remove WP version from css
        $this->getWPBridge()->addFilter('style_loader_src', [$this, 'removeWpVerCssJs'], 9999);
        // remove Wp version from scripts
        $this->getWPBridge()->addFilter('script_loader_src', [$this, 'removeWpVerCssJs'], 9999);
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
            $src = $this->getWPBridge()->removeQueryArg('ver', $src);
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
            $this->getWPBridge()->addAction("manage_edit-${taxonomy}_columns", [$this, 'addColumnId']);
            $this->getWPBridge()->addFilter("manage_${taxonomy}_custom_column", [$this, 'addColumnReturnValue'], 10, 3);
            $this->getWPBridge()->addFilter("manage_edit-${taxonomy}_sortable_columns", [$this, 'addColumnId']);
        }

        foreach (get_post_types() as $ptype) {
            $this->getWPBridge()->addAction("manage_edit-${ptype}_columns", [$this, 'addColumnId']);
            $this->getWPBridge()->addFilter("manage_${ptype}_posts_custom_column", [$this, 'addColumnIdValue'], 10, 3);
            $this->getWPBridge()->addFilter("manage_edit-${ptype}_sortable_columns", [$this, 'addColumnId']);
        }

        $this->getWPBridge()->addAction('manage_media_custom_column', [$this, 'addColumnIdValue'], 10, 2);
        $this->getWPBridge()->addAction('manage_link_custom_column', [$this, 'addColumnId'], 10, 2);
        $this->getWPBridge()->addAction('manage_edit-link-categories_columns', [$this, 'addColumnId']);
        $this->getWPBridge()->addAction('manage_users_columns', [$this, 'addColumnId']);
        $this->getWPBridge()->addAction('manage_edit-comments_columns', [$this, 'addColumnId']);
        $this->getWPBridge()->addAction('manage_comments_custom_column', [$this, 'addColumnIdValue'], 10, 2);

        $this->getWPBridge()->addFilter('manage_media_columns', [$this, 'addColumnId']);
        $this->getWPBridge()->addFilter('manage_link-manager_columns', [$this, 'addColumnId']);
        $this->getWPBridge()->addFilter('manage_link_categories_custom_column', [$this, 'addColumnReturnValue'], 10, 3);
        $this->getWPBridge()->addFilter('manage_users_custom_column', [$this, 'addColumnReturnValue'], 10, 3);
        $this->getWPBridge()->addFilter('manage_edit-comments_sortable_columns', [$this, 'addColumnId']);
    }

    /**
     * Init
     */
    public function init()
    {
        global $wp_version;

        if (version_compare($wp_version, '4.1.0', '<')) {
            throw new \Exception('Your Wordpress version is too old, please upgrade to a newer version');
        }

        $this->getWPBridge()->addThemeSupport('post-formats', ['aside', 'image', 'link', 'quote', 'status']);
        $this->getWPBridge()->addThemeSupport('post-thumbnails');
        $this->getWPBridge()->addThemeSupport('menus');
        $this->getWPBridge()->addThemeSupport('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
        // This theme supports a variety of post formats.

        $this->getWPBridge()->addAction('init', [$this, 'wpHeadCleanup']);
        $this->getWPBridge()->addAction('admin_init', [$this, 'addIdColumn'], 199);

        (new TwigFilter())->init();

        $this->getWPBridge()->addFilter('the_generator', [$this, 'removeRssVersion']);
        $this->getWPBridge()->addFilter('get_image_tag_class', [$this, 'imageTagClassClean'], 0, 4);
        $this->getWPBridge()->addFilter('get_image_tag', [$this, 'imageEditorRemoveHightAndWidth'], 0, 4);
        $this->getWPBridge()->addFilter('the_content', [$this, 'wrapImgInFigure'], 30);
        $this->getWPBridge()->addFilter('post_thumbnail_html', [$this, 'removeImageAttributes'], 10);
        $this->getWPBridge()->addFilter('image_send_to_editor', [$this, 'removeImageAttributes'], 10);

        // Should be allways last.
        $this->getWPBridge()->addFilter('timber_context', [$this, 'addToContext']);
    }

    /**
     * Wordpress conditionals
     *
     * @return array
     */
    public function wpConditionals()
    {
        return [
            'is_home'              => $this->getWPBridge()->isHome(),
            'is_front_page'        => $this->getWPBridge()->isFrontPage(),
            'is_admin'             => $this->getWPBridge()->isAdmin(),
            'is_single'            => $this->getWPBridge()->isSingle(),
            'is_sticky'            => $this->getWPBridge()->isSticky(),
            'get_post_type'        => $this->getWPBridge()->getPostType(),
            'is_single'            => $this->getWPBridge()->isSingle(),
            'is_post_type_archive' => $this->getWPBridge()->isPostTypeArchive(),
            'is_page'              => $this->getWPBridge()->isPage(),
            'is_page_template'     => $this->getWPBridge()->isPageTemplate(),
            'is_category'          => $this->getWPBridge()->isCategory(),
            'is_tag'               => $this->getWPBridge()->isTag(),
            'has_tag'              => $this->getWPBridge()->hasTag(),
            'is_tax'               => $this->getWPBridge()->isTax(),
            'has_term'             => $this->getWPBridge()->hasTerm(),
            'is_author'            => $this->getWPBridge()->isAuthor(),
            'is_date'              => $this->getWPBridge()->isDate(),
            'is_year'              => $this->getWPBridge()->isYear(),
            'is_month'             => $this->getWPBridge()->isMonth(),
            'is_day'               => $this->getWPBridge()->isDay(),
            'is_time'              => $this->getWPBridge()->isTime(),
            'is_archive'           => $this->getWPBridge()->isArchive(),
            'is_search'            => $this->getWPBridge()->isSearch(),
            'is_404'               => $this->getWPBridge()->is404(),
            'is_paged'             => $this->getWPBridge()->isPaged(),
            'is_attachment'        => $this->getWPBridge()->isAttachment(),
            'is_singular'          => $this->getWPBridge()->isSingular(),
            'template_uri'         => $this->getWPBridge()->getTemplateDirectoryUri(),
            'single_cat_title'     => $this->getWPBridge()->singleCatTitle('', false),
        ];
    }
}
