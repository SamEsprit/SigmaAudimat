<?php

namespace Sigma\ApiBundle\Controller;

use Sigma\AdminBundle\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends Controller
{
    public function VoteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id"=>$request->get("user_id")));
        $emission = $em->getRepository("SigmaAdminBundle:Emission")->findOneBy(array("id"=>$request->get("emission_id")));
        $v = $em->getRepository("SigmaAdminBundle:Vote")->findOneBy(array("user"=>$user,"emission"=>$emission));
        if ($v==null)
        {
            $vote= new Vote();
            $vote->setUser($user);
            $vote->setEmission($emission);
            $vote->setVote($request->get("vote"));
            $em->persist($vote);
            $em->flush();
        }
        else
        {
            $em->remove($v);
            $em->flush();
        }
    }
}
