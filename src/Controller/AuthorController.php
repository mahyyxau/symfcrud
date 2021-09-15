<?php
    namespace App\Controller;

    use App\Entity\Author;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;

    class AuthorController extends Controller {

        /**
         * @Route("/authors/new")
         * Method({"GET","POST"})
         */
        // public function new(Request $request){
        //     $category = new Category();
            
        //     $form = $this->createFormBuilder($category)->add('name', TextType::class, ['label'=>'Төрлийн нэр', 'attr'=>['class'=>'form-control']])
        //             ->add('save', SubmitType::class, ['label'=>'Бүртгэх','attr'=>['class'=>'btn btn-primary']])->getForm();

        //     $form ->handleRequest($request);

        //     if($form->isSubmitted() && $form->isValid()){
        //         $category = $form->getData();

        //         $entityManager = $this->getDoctrine()->getManager();
        //         $entityManager->persist($category);
        //         $entityManager->flush();

        //         return $this->redirect('/');
        //     }

        //     return $this->render('categories/new.html.twig', ['form'=>$form->createView()]);
        // }

        /**
         * @Route("/authors/delete/{id}")
         * @Method("DELETE")
         */
        public function delete(Request $request, $id){
            $author = $this->getDoctrine()->getRepository(Author::class)->find($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($author);
            $entityManager->flush();

            $response = new Response();
            $response->send();
        }

        /**
         * @Route("/authors/update/{id}")
         * Method({"GET","POST"})
         */
        public function update(Request $request, $id){
            $author = new Author();
            $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
            
            $form = $this->createFormBuilder($author)->add('name', TextType::class, ['attr'=>['class'=>'form-control']])
                    ->add('save', SubmitType::class, ['label'=>'Засах','attr'=>['class'=>'btn btn-primary']])->getForm();

            $form ->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirect('/');
            }

            return $this->render('authors/update.html.twig', ['form'=>$form->createView()]);
        }

    }