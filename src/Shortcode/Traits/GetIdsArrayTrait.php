<?php
namespace Gwa\Wordpress\Zero\Shortcode\Traits;

/**
 * Trait that provides a method for parsing a comma-separated list of numeric IDs.
 */
trait GetIdsArrayTrait
{
    /**
     * @param string $ids comma separated ids
     * @return array
     */
    protected function getIdsArray($ids)
    {
        $ret = [];

        foreach (explode(',', $ids) as $id) {
            if ($intvalue = (int) $id) {
                $ret[] = $intvalue;
            }
        }

        return $ret;
    }
}
