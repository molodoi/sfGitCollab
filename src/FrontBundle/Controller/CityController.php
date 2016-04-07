<?php

namespace FrontBundle\Controller;

use MainBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CityController extends Controller
{
    public function citiesAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        //if($request->isXmlHttpRequest()){
            $names = array();
            $term = trim(strip_tags($request->get('term')));

            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('MainBundle:City')->findCitiesByNameOrZipcode($term);

            foreach ($entities as $entity)
            {
                //$names[] = $entity->getCityName().' ('.$entity->getCityZipcode().')';
                $names[] = array(
                    //"value" => $entity->getCityName().' ('.$entity->getCityCodeTown().')',
                    "value" => $entity->getCityName().' ('.$entity->getCityZipcode().')',
                    "city" => $entity->getCityNameReal(),
                    "zipcode" => $entity->getCityZipcode(),
                    "longitude" => $entity->getCityLongitudeDeg(),
                    "latitude" => $entity->getCityLatitudeDeg(),
                );
            }

            $response = new JsonResponse();
            $response->setData($names);
            return $response;
        //}

    }
}