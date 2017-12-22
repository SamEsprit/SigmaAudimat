<?php

namespace Sigma\AdminBundle\Controller;

use Sigma\AdminBundle\Entity\Notification;
use Sigma\AdminBundle\Types\NotificationType;
use Sigma\AdminBundle\Types\UpdateNotificationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;

class NotificationController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $Notifications = $em->getRepository('SigmaAdminBundle:Notification')->findAll();
        return $this->render('SigmaAdminBundle:Notification:List.htlm.twig', array('Notifications' => $Notifications));
    }

    public function addAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $Notification = new Notification();
        $form = $this->createForm(NotificationType::class, $Notification);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($Notification);
            $em->flush();
            $server_key = 'AAAAQv0kcqE:APA91bGvdOSUEf2Kh9mELXM9U6Q1UgALrGGnpYixqJodlGS5c7KDJxgscEW0Rp79K8KNft2K2up8LjvFZtFcAdoplkEvjI6KibeDFAmkw_LA6KOqYlrxrytN8bk7Gz6DQvJWBreWs4Mo';
            $client = new Client();
            $client->setApiKey($server_key);
            $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());


            $MobinauteHasDevices = $em->getRepository('SigmaAdminBundle:MobinauteHasDevices')->findAll();
            foreach ($MobinauteHasDevices as $mhd) {
                $message = new Message();
                $message->setPriority('high');
                $message->addRecipient(new Device($mhd->getDevices()->getRegid()));
                $message
                    ->setNotification(new \sngrl\PhpFirebaseCloudMessaging\Notification('Sigmat Audimat', $Notification->getContenu()))
                    ->setData(['title' => "Sigmat Audimat"
                        , 'message' => $Notification->getContenu()
                        , "id" => $Notification->getId(),
                        'sujet'=>$Notification->getSujet()]);
                $client->send($message);
            }
            return $this->redirectToRoute("sigma_admin_Notifications");
        }
        return $this->render('SigmaAdminBundle:Notification:Add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $Notification = $em->getRepository('SigmaAdminBundle:Notification')->find($id);

        $form = $this->createForm(UpdateNotificationType::class, $Notification);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($Notification);
            $em->flush();

            return ($this->redirectToRoute("sigma_admin_Notifications"));
        }

        return $this->render('@SigmaAdmin/Notification/Update.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $Question = $em->getRepository('SigmaAdminBundle:Notification')->find($id);
        $em->remove($Question);
        $em->flush();
        return $this->redirectToRoute("sigma_admin_Notifications");
    }
}
