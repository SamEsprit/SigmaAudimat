<?php

namespace Sigma\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EmissionController extends FOSRestController
{
    public function ListEmissionTvAction(Request $request)
    {
        $JsonResponse = [];
        $datenow = new \DateTime("now", new \DateTimeZone("America/New_York"));
        $datenow->add(new \DateInterval("PT5H"));
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id" => $request->get("user_id")));
        $Chaines = $em->getRepository('SigmaAdminBundle:Chaines')->findBy(array('type' => 'Tv'));
     
        foreach ($Chaines as $Chaine) {
            $Emissions = $this->get('doctrine.orm.entity_manager')
                ->getRepository('SigmaAdminBundle:Emission')
                ->findBy(array('chaines' => $Chaine));

            if (count($Emissions) == 0) {
                $JsonResponse = [];
            } else {
                foreach ($Emissions as $Emission) {
                    $vote = $em->getRepository("SigmaAdminBundle:Vote")->findOneBy(array("emission" => $Emission, "user" => $user));
                    $votes= $em->getRepository("SigmaAdminBundle:Vote")->findBy(array("emission" => $Emission));
                    if ($vote == null) {
                        $v = 0;
                    } else $v = 1;

                   if ($datenow >= $Emission->getDatedebut() and $datenow <= $Emission->getDatefin()) {
                        $JsonResponse[] = [
                            'id' => $Emission->getId(),
                            'libelleEmission' => $Emission->getLibelle(),
                            'libelleChaine' => $Emission->getChaines()->getLibelle(),
                            'description' => $Emission->getDescription(),
                            'logoEmission' => $Emission->getLogo(),
                            'logoChaine' => $Emission->getChaines()->getLogo(),
                            'dateDebut' => $Emission->getDatedebut()->format('Y-m-d H:i'),
                            'dateFin' => $Emission->getDatefin()->format('Y-m-d H:i'),
                            'voteuser' => $v,
                            'votes'=>count($votes),
                        ];
                    }
                }
            }
        }
        return new JsonResponse($JsonResponse);
    }

    public function ListEmissionRadioAction(Request $request)
    {
        $JsonResponse = [];
        $em = $this->getDoctrine()->getManager();
        $datenow = new \DateTime("now", new \DateTimeZone("America/New_York"));
        $datenow->add(new \DateInterval("PT5H"));


        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id" => $request->get("user_id")));
        $Chaines = $em->getRepository('SigmaAdminBundle:Chaines')->findBy(array('type' => 'Radio'));

        foreach ($Chaines as $Chaine) {
            $Emissions = $this->get('doctrine.orm.entity_manager')
                ->getRepository('SigmaAdminBundle:Emission')
                ->findBy(array('chaines' => $Chaine));

            if (count($Emissions) == 0) {
                $JsonResponse = [];
            } else {
                foreach ($Emissions as $Emission) {

                    $vote = $em->getRepository("SigmaAdminBundle:Vote")->findOneBy(array("emission" => $Emission, "user" => $user));

                    $votes= $em->getRepository("SigmaAdminBundle:Vote")->findBy(array("emission" => $Emission));
                    if ($vote == null) {
                        $v = 0;
                    } else $v = 1;


                    if ($datenow >= $Emission->getDatedebut() and $datenow <= $Emission->getDatefin()) {
                        $JsonResponse[] = [
                            'id' => $Emission->getId(),
                            'libelleEmission' => $Emission->getLibelle(),
                            'libelleChaine' => $Emission->getChaines()->getLibelle(),
                            'description' => $Emission->getDescription(),
                            'logoEmission' => $Emission->getLogo(),
                            'logoChaine' => $Emission->getChaines()->getLogo(),
                            'dateDebut' => $Emission->getDatedebut()->format('Y-m-d H:i:s'),
                            'dateFin' => $Emission->getDatefin()->format('Y-m-d H:i:s'),
                            'voteuser' => $v,
                            'votes'=>count($votes)
                        ];
                    }
                }
            }
        }
        return new JsonResponse($JsonResponse);
    }

    public function ListEmissionByChaineAction($id,Request $request)
    {
        $JsonResponse = [];
        $em = $this->getDoctrine()->getManager();
        $Chaine = $em->getRepository('SigmaAdminBundle:Chaines')->find($id);
        $Emissions = $this->get('doctrine.orm.entity_manager')
            ->getRepository('SigmaAdminBundle:Emission')
            ->findBy(array('chaines' => $Chaine));
        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id" => $request->get("user_id")));
        if (count($Emissions) == 0) {
            $JsonResponse = [];
        } else {
            foreach ($Emissions as $Emission) {
                $vote = $em->getRepository("SigmaAdminBundle:Vote")->findOneBy(array("emission" => $Emission, "user" => $user));

                $votes= $em->getRepository("SigmaAdminBundle:Vote")->findBy(array("emission" => $Emission));
                if ($vote == null) {
                    $v = 0;
                } else $v = 1;
                $JsonResponse[] = [
                    'id' => $Emission->getId(),
                    'libelle' => $Emission->getLibelle(),
                    'description' => $Emission->getDescription(),
                    'logo' => $Emission->getLogo(),
                    'dateDebut' => $Emission->getDatedebut()->format('Y-m-d H:i'),
                    'dateFin' => $Emission->getDatefin()->format('Y-m-d H:i'),
                    'voteuser' => $v,
                    'votes'=>count($votes),
                ];
            }
        }


        return new JsonResponse($JsonResponse);
    }
}
