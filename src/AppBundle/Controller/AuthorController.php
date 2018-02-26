<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\BlogPost;
use AppBundle\Entity\Author;
use Symfony\Component\HttpFoundation\Response;


class AuthorController extends Controller
{
    public function listPostsAction(Request $request, $authorId)  {


        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM blog_post WHERE author_id = :id");
        $statement->bindValue('id', $authorId);
        $statement->execute();
        $postFromAuthor = $statement->fetchAll();

        // Make join... Too drunk for now
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM author WHERE id = :id");
        $statement->bindValue('id', $authorId);
        $statement->execute();
        $author = $statement->fetchAll();

        // replace this example code with whatever you need
        return $this->render('default/authorPosts.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'author' => $author,
            'posts'=>$postFromAuthor



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
