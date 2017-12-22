<?php

namespace Sigma\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StatistiqueController extends Controller
{
    public function NotifictionStatistiqueAction(Request $request)
    {

        $stmt = $this->getDoctrine()->getEntityManager()
            ->getConnection()
            ->prepare("SELECT count(e.id) AS count, n.contenu as notification, e.libelle AS Libelle FROM user_has_notification AS uhn "
                . "INNER JOIN emission AS e ON e . id = uhn . emission_id "
                . "INNER JOIN chaines AS c ON e . chaines_id = c . id "
                . "INNER JOIN notification AS n ON n.id=uhn.notification_id "
                . "WHERE notification_id =" . $request->get("notification_id")
                . " GROUP BY e . id"
            );
        $stmt->execute();

        $stat = $stmt->fetchAll();
        $all = 0;
        foreach ($stat as $value) {
            $all = $all + intval($value['count']);
        }
        foreach ($stat as $value) {
        $dataStat[] = [
            "libelle" => $value['Libelle'],
            "pourcentage"=>(intval($value['count']) / $all) * 100
            ];
        }
        return new JsonResponse($dataStat);

    }
}
