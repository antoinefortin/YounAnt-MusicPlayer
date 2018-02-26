<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\BlogPost;
use AppBundle\Entity\Author;


class BlogPostController extends Controller
{
    public function createAction(Request $request)
    {
        // creates a task and gives it some dummy data for this example
        $blogItem = new BlogPost();
        $blogItem->setTitle('New blogposts');


        $form = $this->createFormBuilder($blogItem)
            ->add('title', TextType::class)
            ->add('header', TextType::class)
            ->add('body', TextType::class)
            ->add('categories', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Post Blog'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $blogPost = $form->getData();

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->redirectToRoute('blog_list');
    }

        // replace this example code with whatever you need
        return $this->render('default/createBlogPost.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'form' => $form->createView()
        ]);


    }

    public function listAction(Request $request)
    {


        $query = "Select * from blog_post";
        $em = $this->getDoctrine()->getEntityManager();
        $conn = $em->getConnection();

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $allPosts = $stmt->fetchAll();



        // replace this example code with whatever you need
        return $this->render('default/blog.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'posts' => $allPosts
        ]);
    }


        public function singleAction(Request $request, $postId)
        {


                // Get single post
                $singlePost = $this->getDoctrine()
                ->getRepository(BlogPost::class)
                ->find($postId);

                // Build author
                $author = array();
                $author["id"] = $singlePost->getAuthor()->getId();
                $author["fullName"] = $singlePost->getAuthor()->getCompleteName();
                $author["location"] = $singlePost->getAuthor()->getCity() . " - " . $singlePost->getAuthor()->getCountry();
                $author["image"] = $singlePost->getAuthor()->getProfilePicture();

            // replace this example code with whatever you need
            return $this->render('default/singlePost.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
                'singlePost' => $singlePost,
                'author' => $author
            ]);
        }


        public function listAuthorPostsAction(Request $request, $authorId)  {


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

}
