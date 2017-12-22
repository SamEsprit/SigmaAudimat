<?php

namespace Sigma\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get; // N'oublons pas d'inclure Get
class RadioController extends FOSRestController
{
    public function ListRadioAction()
    {
        $JsonResponse = [];
        $em = $this->getDoctrine()->getManager();
        $Chaines = $em->getRepository('SigmaAdminBundle:Chaines')->findBy(array('type' => 'Radio'));
        foreach ($Chaines as $Chaine) {

            $JsonResponse[] = [
                'id' => $Chaine->getId(),
                'libelle' => $Chaine->getLibelle(),
                'description' => $Chaine->getDescription(),
                'logo' => $Chaine->getLogo(),
                'web' => $Chaine->getWeb()
            ];

        }


        return new JsonResponse($JsonResponse);
    }
}
