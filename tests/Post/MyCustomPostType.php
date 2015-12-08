<?php
namespace Gwa\Wordpress\Zero\Test\Post;

use Gwa\Wordpress\Zero\Post\AbstractCustomPostType;

class MyCustomPostType extends AbstractCustomPostType
{
    /**
     * @return string
     */
    public function getPostType()
    {
        return 'foo';
    }

    /**
     * @return string
     */
    public function getSingular()
    {
        return 'foo';
    }

    /**
     * @return string
     */
    public function getPlural()
    {
        return 'foos';
    }

    /**
     * @return string
     * @link https://developer.wordpress.org/resource/dashicons/
     */
    public function getIcon()
    {
        return 'dashicons-email';
    }
}
