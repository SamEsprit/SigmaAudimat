<?php

namespace Sigma\ApiBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;

use Sigma\AdminBundle\Entity\Devices;
use Sigma\AdminBundle\Entity\MobinauteHasDevices;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;



class MobinauteController extends FOSRestController
{
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->addRole('ROLE_MOBINAUTE');
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm([
            'csrf_protection' => false
        ]);
        $form->setData($user);
        $form->submit($request->request->all());

        if (!$form->isValid()) {

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }

            return $form;
        }

        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }
        $user->setLastLogin(null);
        $userManager->updateUser($user);

        $response = new JsonResponse(
            [


            ]
        );

        $dispatcher->dispatch(
            FOSUserEvents::REGISTRATION_COMPLETED,
            new FilterUserResponseEvent($user, $request, $response)
        );

        return $response;

    }

    public function DeviceIdAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SigmaAdminBundle:User')->findOneBy(array('id' => $request->get("user_id")));

        $d = $em->getRepository('SigmaAdminBundle:Devices')->findOneBy(array('deviceid' => $request->get("device_id")));

        if ($d == null) {
            $d = new Devices();
            $d->setDeviceid($request->get("device_id"));
            $d->setType($request->get("type"));
            $em->persist($d);
            $em->flush();


            $Mobinaute_has_devices = new MobinauteHasDevices();
            $Mobinaute_has_devices->setDevices($d);
            $Mobinaute_has_devices->setUser($user);
            $em->persist($Mobinaute_has_devices);
            $em->flush();

        } else {
            $Mobinaute_has_devices = $em->getRepository('SigmaAdminBundle:MobinauteHasDevices')->findOneBy(array('devices' => $d));
            $Mobinaute_has_devices->setUser($user);
            $em->persist($Mobinaute_has_devices);
            $em->flush();
        }
    }

    public function RegIdAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $d = $em->getRepository('SigmaAdminBundle:Devices')->findOneBy(array('deviceid' => $request->get("device_id")));
        $d->setRegid($request->get("reg_id"));
        $em->persist($d);
        $em->flush();

    }

    public function ProfileAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SigmaAdminBundle:User')->findOneBy(array('id' => $request->get("user_id")));
        $userHasNotification = $em->getRepository('SigmaAdminBundle:UserHasNotification')->findBy(array('users' => $user,'participation' => 1));
        $point = $user->getPoint();
        $participation = count($userHasNotification);

            $JsonResponse = [
                'point' => $point,
                'participation' => $participation
            ];

        return new JsonResponse($JsonResponse);
    }

    public function ProfileInformationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SigmaAdminBundle:User')->findOneBy(array('id' => $request->get("user_id")));
        $JsonResponse = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'fullname' => $user->getName(),
            'tel' => $user->getTel()
        ];
        return new JsonResponse($JsonResponse);
    }

    public function UpdateProfileInformationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $fosuser = $userManager->findUserBy(array('id' => $request->get("user_id")));

        $fosuser->setEmail($request->get("email"));
        $fosuser->setUsername($request->get("username"));
        $userManager->updateUser($fosuser, true);

        $user = $em->getRepository('SigmaAdminBundle:User')->findOneBy(array('id' => $request->get("user_id")));
        $user->setName($request->get("fullname"));
        $user->setTel($request->get("tel"));
        $em->persist($user);
        $em->flush();


    }

    public function VerifUserNameAction(Request $request)
    {

        $userManager = $this->get('fos_user.user_manager');
        $fosuser = $userManager->findUserByUsername($request->get("usernameOremail"));
        if ($fosuser != null)
            $response = new JsonResponse(
                [
                    "mesage" => "Existe"

                ]
            );
        else
            $response = new JsonResponse(
                [
                    "mesage" => "Not Existe"

                ]
            );
        return $response;
    }

    public function VerifEmailAction(Request $request)
    {

        $userManager = $this->get('fos_user.user_manager');
        $fosuser = $userManager->findUserByEmail($request->get("usernameOremail"));
        if ($fosuser != null)
            $response = new JsonResponse(
                [
                    "mesage" => "Existe"

                ]
            );
        else
            $response = new JsonResponse(
                [
                    "mesage" => "NotExiste"

                ]
            );
        return $response;
    }
}