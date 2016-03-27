<?php

namespace BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        return $this->render('BackBundle:Default:index.html.twig');
    }
}
