<?php

namespace Sigma\AdminBundle\Controller;

use Sigma\AdminBundle\Entity\Chaines;
use Sigma\AdminBundle\Types\ChainesType;
use Sigma\AdminBundle\Types\UpdateChainesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
class RadioController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $Chaines = $em->getRepository('SigmaAdminBundle:Chaines')->findBy(array('type'=>'Radio'));
        return $this->render('SigmaAdminBundle:Radios:List.htlm.twig', array('Chaines'=>$Chaines));
    }
    public function addAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $Chaine = new Chaines();
        $form = $this->createForm( ChainesType::class, $Chaine);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $Chaine->getLogo();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('logoRadio_directory'),
                $fileName
            );
            $Chaine->setLogo($fileName);
            $Chaine->setType("Radio");
            $em->persist($Chaine);
            $em->flush();
            return $this->redirectToRoute("sigma_admin_Radios");
        }
        return $this->render('SigmaAdminBundle:Radios:Add.html.twig', array(
            'form' => $form->createView()
        ));
    }
    public function updateAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $Chaine = $em->getRepository('SigmaAdminBundle:Chaines')->find($id);
        $Chaine->setLogo(
            new File($this->getParameter('logoRadio_directory').'/'.$Chaine->getLogo()));

        $form = $this->createForm(UpdateChainesType::class, $Chaine);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $Chaine->getLogo();
            $Chaine->setType("Radio");
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('logoChaine_directory'),
                $fileName
            );
            $Chaine->setLogo($fileName);
            $em->persist($Chaine);
            $em->flush();

            return ($this->redirectToRoute("sigma_admin_Radios"));
        }

        return $this->render('@SigmaAdmin/Chaines/Update.html.twig', array(
            'form' => $form->createView()
        ));
    }
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $Chaine= $em->getRepository('SigmaAdminBundle:Chaines')->find($id);
        $em->remove($Chaine);
        $em->flush();
        return $this->redirectToRoute("sigma_admin_Radios");
    }

}
