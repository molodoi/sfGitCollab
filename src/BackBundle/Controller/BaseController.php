<?php
namespace BackBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BaseController extends Controller{

    /**
     * CHECK IF USER HAVE ROLE ADMIN TO ACCESS BACKEND
     */
    public function grantAccessUserSecurity()
    {
        $securityContext = $this->container->get('security.context');
        if (!$securityContext->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Need ROLE_ADMIN!');
        }
    }

}