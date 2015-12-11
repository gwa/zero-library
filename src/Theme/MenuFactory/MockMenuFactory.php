<?php
namespace Gwa\Wordpress\Zero\Theme\MenuFactory;

class MockMenuFactory implements MenuFactoryContract
{
    public function create($slug)
    {
        $menu = new \stdClass;
        $menu->slug = $slug;
        return $menu;
    }
}
