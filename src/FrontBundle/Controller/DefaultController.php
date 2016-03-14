<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /**
         * Flash bag message type : success, info, warning, danger - BS3
         */
        /*$this->get('session')->getFlashBag()->set(
            'success',
            array(
                'title' => 'Great!',
                'message' => 'Test success message.'
            )
        );*/

        return $this->render('FrontBundle:Default:index.html.twig');
    }
}
