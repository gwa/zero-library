<?php

namespace Gwa\Wordpress\Template\Zero\Library\Timber;

/**
 * Zero - a PHP 5.4 Wordpress Theme.
 *
 * @author      Daniel Bannert <bannert@greatwhiteark.com>
 * @copyright   2015 Great White Ark
 *
 * @link        http://www.greatwhiteark.com
 *
 * @license     MIT
 */

use TimberPost;

/**
 * Post.
 *
 * @author  GWA
 *
 */
class Post extends TimberPost
{
    public function getEditUrl()
    {
        return ($this->can_edit() ? get_edit_post_link($this->ID) : false);
    }

    /**
     * @param  string $dateFormat
     *
     * @return string
     */
    public function getDate($dateFormat = '')
    {
        $df = $dateFormat ? $dateFormat : get_option('date_format');
        $theDate = (string) mysql2date($df, $this->post_date);

        return apply_filters('get_the_date', $theDate, $dateFormat);
    }

    /**
     * @param  string $dateFormat
     * @return string
     */
    public function getModifiedDate($dateFormat = '')
    {
        $df = $dateFormat ? $dateFormat : get_option('date_format');
        $theTime = $this->getModifiedTime($df, null, $this->ID, true);

        return apply_filters('get_the_modified_date', $theTime, $dateFormat);
    }

    /**
     * @param string $timeFormat
     *
     * @return string
     */
    public function getModifiedTime($timeFormat = '')
    {
        $tf = $timeFormat ? $timeFormat : get_option('time_format');
        $theTime = get_post_modified_time($tf, false, $this->ID, true);

        return apply_filters('get_the_modified_time', $theTime, $timeFormat);
    }
}
