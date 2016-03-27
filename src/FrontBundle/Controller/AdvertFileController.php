<?php

namespace FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\AdvertFile;

/**
 * AdvertFile controller.
 *
 */
class AdvertFileController extends Controller
{

    /**
     * Deletes a AdvertFile entity.
     *
     */
    public function deleteOneFileAction(Request $request, AdvertFile $advertFile)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array('error' => true));
            }
            throw $this->createAccessDeniedException();
        }else {
            $em = $this->getDoctrine()->getManager();
            $file = $em->getRepository('MainBundle:AdvertFile')->find($advertFile);
            $em->remove($file);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array('success' => true));
            }
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
