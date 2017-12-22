<?php

namespace Sigma\AdminBundle\Controller;

use Sigma\AdminBundle\Entity\Emission;
use Sigma\AdminBundle\Types\EmissionType;
use Sigma\AdminBundle\Types\UpdateEmissionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

class EmissionController extends Controller
{
    public function listAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $chaine = $em->getRepository('SigmaAdminBundle:Chaines')->findOneBy(array('id' => $id));
        $Emmissions = $em->getRepository('SigmaAdminBundle:Emission')->findBy(array('chaines' => $chaine));
        return $this->render('SigmaAdminBundle:Emission:List.htlm.twig', array('Emmissions' => $Emmissions, 'id' => $id));
    }

    public function addAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $chaine = $em->getRepository('SigmaAdminBundle:Chaines')->findOneBy(array('id' => $id));
        $Emission = new Emission();
        $form = $this->createForm(EmissionType::class, $Emission);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $Emission->getLogo();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('logoEmission_directory'),
                $fileName
            );
            $Emission->setLogo($fileName);
            $Emission->setChaines($chaine);
            $em->persist($Emission);
            $em->flush();
            return $this->redirectToRoute("sigma_admin_Emmission", array('id' => $id));
        }
        return $this->render('SigmaAdminBundle:Emission:Add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function updateAction(Request $request, $id, $idE)
    {

        $em = $this->getDoctrine()->getManager();
        $Chaine = $em->getRepository('SigmaAdminBundle:Chaines')->find($id);
        $Emission = $em->getRepository('SigmaAdminBundle:Emission')->find($idE);
        $Emission->setLogo(
            new File($this->getParameter('logoemission_directory') . '/' . $Emission->getLogo()));

        $form = $this->createForm(UpdateEmissionType::class, $Emission);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $Emission->getLogo();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('logoEmission_directory'),
                $fileName
            );
            $Emission->setChaines($Chaine);
            $Emission->setLogo($fileName);
            $em->persist($Emission);
            $em->flush();

            return $this->redirectToRoute("sigma_admin_Emmission", array('id' => $id));
        }

        return $this->render('@SigmaAdmin/Emission/Update.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteAction(Request $request, $id,$idE)
    {

        $em = $this->getDoctrine()->getManager();
        $Chaine = $em->getRepository('SigmaAdminBundle:Emission')->find($idE);
        $em->remove($Chaine);
        $em->flush();
        return $this->redirectToRoute("sigma_admin_Chaines", array('id' => $id));
    }
}
