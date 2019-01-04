<?php
    namespace App\Controller;
     use App\Entity\Article;

     use Symfony\Component\HttpFoundation\JsonResponse;
     use Symfony\Component\HttpFoundation\RedirectResponse;
     use Symfony\Component\HttpFoundation\Response;
     use Symfony\Component\HttpFoundation\Request;
     use Symfony\Component\Routing\Annotation\Route;
     use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
     use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
     use Symfony\Component\Form\Extension\Core\Type\TextType;
     use Symfony\Component\Form\Extension\Core\Type\TextareaType;
     use Symfony\Component\Form\Extension\Core\Type\SubmitType;


  class ArticleController extends AbstractController {
      /**
       * @Route("/", name="article_list")
       * @Method({"GET"})
       */
      public function index(){
          $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
          return $this->render('articles/index.html.twig', array('articles' => $articles));
      }

      /**
       * @Route("/article/new", name="new_article")
       * Method({"GET", "POST"})
       *
       * @param Request $request
       *
       * @return RedirectResponse|Response
       */
      public function new(Request $request){
          $article = new Article();

          $form = $this->createFormBuilder($article)
              ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
              ->add('body', TextareaType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
              ->add('save', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary mt-3')))->getForm();

          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()){
              $article = $form->getData();
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($article);
              $entityManager->flush();

              return $this->redirectToRoute('article_list');
          }

          return $this->render('articles/new.html.twig', array('form' => $form->createView()));
      }


      /**
       * @Route("/article/{id}", name="article_show")
       * @param Article $article
       *
       * @return Response
       */
      public function show(Article $article){
          return $this->render('articles/show.html.twig', array('article' => $article));
      }

      /**
       * @Route("/article/delete/{id}", name="delete_article")
       * @Method({"DELETE"})
       *
       * @param Request $request
       * @param Article $article
       *
       */
       public function delete(Request $request, Article $article){
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->remove($article);
           $entityManager->flush();

           $response = new Response();
           $response->send();
       }


      /**
       * @Route("/article/like/{id}", name="like_article")
       *
       * @param Request $request
       *
       * @param Article $article
       * @return JsonResponse
       */
      public function likeAction(Request $request, Article $article)
      {
          $likeStatus = $request->get('liked');

          $article->setLiked($likeStatus);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();

          return new JsonResponse('', 204);
      }














































        // this is a way to test your database

//        /**
//         * @Route("/article/save")
//         */
//        public function save(){
//            $entityManager = $this->getDoctrine()->getManager();
//
//            $article = new Article();
//
//            $article->setTitle('Article two');
//            $article->setBody('This is the body for our article two');
//
//            $entityManager->persist($article);
//
//            $entityManager->flush();
//
//            return new Response('Saved an article with the id of'.$article->getId());
//        }
    }



