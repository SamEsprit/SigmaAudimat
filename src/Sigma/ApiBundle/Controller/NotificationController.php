<?php

namespace Sigma\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sigma\AdminBundle\Entity\DevicesHasNotification;
use Sigma\AdminBundle\Entity\User;
use Sigma\AdminBundle\Entity\UserHasNotification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class NotificationController extends FOSRestController
{
    public function VuAction(Request $request)
    {
        $datenow = new \DateTime("now", new \DateTimeZone("America/New_York"));
        $datenow->add(new \DateInterval("PT5H"));
        $em = $this->getDoctrine()->getManager();

        $notification = $em->getRepository("SigmaAdminBundle:Notification")->findOneBy(array("id" => $request->get("notification_id")));

        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id" => $request->get("user_id")));
        $userHasNotification = $em->getRepository("SigmaAdminBundle:UserHasNotification")->findOneBy(array("users" => $user, "notification" => $notification));
        if ($userHasNotification == null) {
            $userHasNotification = new UserHasNotification();
            $userHasNotification->setUsers($user);
            $userHasNotification->setNotification($notification);
            $userHasNotification->setVu(true);
            $userHasNotification->setParticipation(false);
            $userHasNotification->setDate($datenow);
            $em->persist($userHasNotification);
            $em->flush();
        } else {
            $userHasNotification->setVu(true);
            $userHasNotification->setDate($datenow);
            $em->persist($userHasNotification);
            $em->flush();
        }
    }

    public function NotVuAction(Request $request)
    {
        $datenow = new \DateTime("now", new \DateTimeZone("America/New_York"));
        $datenow->add(new \DateInterval("PT5H"));
        $em = $this->getDoctrine()->getManager();

        $notification = $em->getRepository("SigmaAdminBundle:Notification")->findOneBy(array("id" => $request->get("notification_id")));

        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id" => $request->get("user_id")));
        $userHasNotification = $em->getRepository("SigmaAdminBundle:UserHasNotification")->findOneBy(array("users" => $user, "notification" => $notification));
        if ($userHasNotification == null) {
            $userHasNotification = new UserHasNotification();
            $userHasNotification->setUsers($user);
            $userHasNotification->setNotification($notification);
            $userHasNotification->setVu(false);
            $userHasNotification->setParticipation(false);
            $userHasNotification->setDate($datenow);
            $em->persist($userHasNotification);
            $em->flush();
        } else {
            $userHasNotification->setVu(false);
            $userHasNotification->setDate($datenow);
            $em->persist($userHasNotification);
            $em->flush();
        }
    }

    public function RepondreAction(Request $request)
    {
        $datenow = new \DateTime("now", new \DateTimeZone("America/New_York"));
        $datenow->add(new \DateInterval("PT5H"));
        $em = $this->getDoctrine()->getManager();
        $notification = $em->getRepository("SigmaAdminBundle:Notification")->findOneBy(array("id" => $request->get("notification_id")));
        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id" => $request->get("user_id")));
        $emission = $em->getRepository("SigmaAdminBundle:Emission")->findOneBy(array("id" => $request->get("emission_id")));

        $deviceHasNotification = $em->getRepository("SigmaAdminBundle:UserHasNotification")->findOneBy(array("users" => $user, "notification" => $notification));
        $deviceHasNotification->setParticipation(true);
        $deviceHasNotification->setEmissions($emission);
        $deviceHasNotification->setDate($datenow);
        $em->persist($deviceHasNotification);
        $em->flush();

        $user->setPoint($user->getPoint() + $notification->getPoint());
        $em->persist($user);
        $em->flush();
    }

    public function ListNotificationAction(Request $request)
    {   $JsonResponse=[];
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id" => $request->get("user_id")));
        $UserHasNotification = $em->getRepository("SigmaAdminBundle:UserHasNotification")->findBy(array("users" => $user), array('id' => 'desc'));

        foreach ($UserHasNotification as $uhn) {


            $JsonResponse[] = [
                'participation' => $uhn->isParticipation(),
                'vu' => $uhn->isVu(),
                'notificataion' => [
                    'id' => $uhn->getNotification()->getId(),
                    'title' => "Sigmat Audimat",
                    'message' => $uhn->getNotification()->getContenu(),
                    'sujet' => $uhn->getNotification()->getSujet()]
            ];
        }

        return new JsonResponse($JsonResponse);
    }

    public function ResponseNotificationAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $Chaines = null;
        switch ($request->get("sujet")) {
            case "Emission Tv":
                $Chaines = $em->getRepository('SigmaAdminBundle:Chaines')->findBy(array('type' => 'Tv'));
                break;
            case  "Emission Radio":
                $Chaines = $em->getRepository('SigmaAdminBundle:Chaines')->findBy(array('type' => 'Radio'));
                break;
        }
        $JsonResponse = [];
        $datenow = new \DateTime("now", new \DateTimeZone("America/New_York"));
        $datenow->add(new \DateInterval("PT5H"));
        foreach ($Chaines as $Chaine) {
            $Emissions = $this->get('doctrine.orm.entity_manager')
                ->getRepository('SigmaAdminBundle:Emission')
                ->findBy(array('chaines' => $Chaine));

            if (count($Emissions) == 0) {
                $JsonResponse = [];
            } else {
                foreach ($Emissions as $Emission) {
                    if ($datenow >= $Emission->getDatedebut() and $datenow <= $Emission->getDatefin()) {
                        $JsonResponse[] = [
                            'id' => $Emission->getId(),
                            'libelle' => $Emission->getLibelle(),
                        ];
                    }
                }
            }
        }
        return new JsonResponse($JsonResponse);
    }

    public function notificationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $notification = $em->getRepository("SigmaAdminBundle:Notification")->findOneBy(array("id" => $request->get("notification_id")));

        $user = $em->getRepository("SigmaAdminBundle:User")->findOneBy(array("id" => $request->get("user_id")));

        $userHasNotification = $em->getRepository("SigmaAdminBundle:UserHasNotification")->findOneBy(array("users" => $user, "notification" => $notification));

        if ($userHasNotification == null) {
            $JsonResponse = [
                'participation' => false,
                'vu' => false
            ];

        } else {

            $JsonResponse = [
                'participation' => $userHasNotification->isParticipation(),
                'vu' => $userHasNotification->isVu()
            ];
        }
        return new JsonResponse($JsonResponse);

    }
}
