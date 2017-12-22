<?php

namespace Sigma\AdminBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatistiqueController extends Controller
{
    public function StatistiqueEmissionTvByVoteAction()
    {
        // Chart
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $chaines = $em->getRepository("SigmaAdminBundle:Chaines")->findBy(array("type" => "Tv"));
        foreach ($chaines as $chaine) {
            $emissions = $em->getRepository("SigmaAdminBundle:Emission")->findBy(array("chaines" => $chaine));
            foreach ($emissions as $emission) {

                $emissionv = $em->getRepository("SigmaAdminBundle:Vote")->findBy(array("emission" => $emission));
                $emissionvote = count($emissionv);
                $a = array($emission->getLibelle(), ($emissionvote / count($emissions)));
                array_push($data, $a);
            }
        }


        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->series(array(array("name" => "Tv", 'type' => 'pie', "data" => $data)));

        $ob->title->text("Statistique vote emission Tv.");
        return $this->render('SigmaAdminBundle:Statistique:StatistiqueTvByVote.html.twig', array(
            'chart' => $ob
        ));
    }

    public function StatistiqueEmissionRadioByVoteAction()
    {
        // Chart
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $chaines = $em->getRepository("SigmaAdminBundle:Chaines")->findBy(array("type" => "Radio"));
        foreach ($chaines as $chaine) {
            $emissions = $em->getRepository("SigmaAdminBundle:Emission")->findBy(array("chaines" => $chaine));
            foreach ($emissions as $emission) {
                $emissionv = $em->getRepository("SigmaAdminBundle:Vote")->findBy(array("emission" => $emission));
                $emissionvote = count($emissionv);
                $a = array($emission->getLibelle(), ($emissionvote / count($emissions)));
                array_push($data, $a);
            }
        }

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text("Statistique vote emission Radio.");
        $ob->series(array(array("name" => "Radio", 'type' => 'pie', "data" => $data)));

        return $this->render('SigmaAdminBundle:Statistique:StatistiqueRadioByVote.html.twig', array(
            'chart' => $ob
        ));
    }

    public function NotifictionStatistiqueAction($id)
    {

        $stmt = $this->getDoctrine()->getEntityManager()
            ->getConnection()
            ->prepare("SELECT count(e.id) AS count, n.contenu as notification, e.libelle AS Libelle FROM user_has_notification AS uhn "
                . "INNER JOIN emission AS e ON e . id = uhn . emission_id "
                . "INNER JOIN chaines AS c ON e . chaines_id = c . id "
                . "INNER JOIN notification AS n ON n.id=uhn.notification_id "
                . "WHERE notification_id =" . $id
                . " GROUP BY e . id"
            );
        $stmt->execute();

        $stat = $stmt->fetchAll();
        $all = 0;
        foreach ($stat as $value) {
            $all=$all+intval($value['count']);
        }
        $dataStat= array();
        $XAxisData=array();
        foreach ($stat as $value) {
            $title = $value['notification'];
            $a = array($value['Libelle'], (intval($value['count']) / $all) * 100);
            $sXAxisData=array($value['Libelle']);
            array_push($XAxisData, $sXAxisData);
            array_push($dataStat, $a);
        }
        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text($title);
        $ob->plotOptions->bar(
            array(
                'dataLabels' => array(
                    'enabled' => true
                )
            )
        );
        $ob->xAxis->categories($XAxisData);
        $series = array(
            array("name" => "Tv", "data" => $dataStat)
        );
        $ob->chart->type("bar");

        $ob->series($series);

        return $this->render('SigmaAdminBundle:Statistique:StatistiqueNotification.html.twig', array(
            'chart' => $ob
        ));
    }
}
