<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\BlogPost;
use AppBundle\Entity\Author;
use Symfony\Component\HttpFoundation\Response;


class TuneController extends Controller
{

    public function showSingleTuneAction(Request $request, $tuneId)  {


        $query = "Select * from tune where id = $tuneId";

        $em = $this->getDoctrine()->getEntityManager();
        $conn = $em->getConnection();

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $singleTune = $stmt->fetchAll();
        dump($singleTune[0]);


        // replace this example code with whatever you need
        return $this->render('default/singleTune.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'singleTune' => $singleTune[0]
        ]);
    }

    public function listTunesAction(Request $request)  {



        $query = "Select * from tune";

        $em = $this->getDoctrine()->getEntityManager();
        $conn = $em->getConnection();

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $allTunes = $stmt->fetchAll();

        dump($allTunes);
        // replace this example code with whatever you need
        return $this->render('default/tunes.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tunes' => $allTunes,

        ]);
    }



    public function listActions(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
