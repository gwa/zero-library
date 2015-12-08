<?php
namespace Gwa\Wordpress\Zero\Test\Controller;

use Gwa\Wordpress\Zero\Controller\AbstractController;

class MyController extends AbstractController
{
    public function getContext()
    {
        return [];
    }

    public function getTemplates()
    {
        return [];
    }
}
