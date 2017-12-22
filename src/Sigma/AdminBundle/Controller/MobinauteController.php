<?php

namespace Sigma\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MobinauteController extends Controller
{
    public function listAction()
    {


        $Users = $this->findByRole("ROLE_MOBINAUTE");
        return $this->render('SigmaAdminBundle:Mobinaute:List.htlm.twig', array('Users' => $Users));

    }

    public function enableAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SigmaAdminBundle:User')->findOneBy(array('id' => $id));
        $user->setEnabled(true);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute("sigma_admin_list_mobinaute");
    }

    public function disableAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SigmaAdminBundle:User')->findOneBy(array('id' => $id));
        $user->setEnabled(false);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute("sigma_admin_list_mobinaute");
    }

    public function findByRole($role)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from("SigmaAdminBundle:User", 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');

        return $qb->getQuery()->getResult();
    }

}
