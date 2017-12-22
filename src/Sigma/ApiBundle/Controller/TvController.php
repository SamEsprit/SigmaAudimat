<?php

namespace Sigma\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\Validator\Constraints\DateTime;

class TvController extends Controller
{


    /**
     * @Get("/Tv")
     */
    public function ListTvAction()
    {
        $JsonResponse = [];
        $em = $this->getDoctrine()->getManager();
        $Chaines = $em->getRepository('SigmaAdminBundle:Chaines')->findBy(array('type' => 'Tv'));
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
