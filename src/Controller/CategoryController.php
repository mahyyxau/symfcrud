<?php
    namespace App\Controller;

    use App\Entity\Category;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;

    class CategoryController extends Controller {

        /**
         * @Route("/categories/new")
         * Method({"GET","POST"})
         */
        public function new(Request $request){
            $category = new Category();
            
            $form = $this->createFormBuilder($category)->add('name', TextType::class, ['label'=>'Төрлийн нэр', 'attr'=>['class'=>'form-control']])
                    ->add('save', SubmitType::class, ['label'=>'Бүртгэх','attr'=>['class'=>'btn btn-primary']])->getForm();

            $form ->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $category = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);
                $entityManager->flush();

                return $this->redirect('/');
            }

            return $this->render('categories/new.html.twig', ['form'=>$form->createView()]);
        }

        /**
         * @Route("/categories/delete/{id}")
         * @Method("DELETE")
         */
        public function delete(Request $request, $id){
            $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();

            $response = new Response();
            $response->send();
        }

        /**
         * @Route("/categories/update/{id}")
         * Method({"GET","POST"})
         */
        public function update(Request $request, $id){
            $category = new Category();
            $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
            
            $form = $this->createFormBuilder($category)->add('name', TextType::class, ['label'=>'Төрлийн нэр', 'attr'=>['class'=>'form-control']])
                    ->add('save', SubmitType::class, ['label'=>'Засах','attr'=>['class'=>'btn btn-primary']])->getForm();

            $form ->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirect('/');
            }

            return $this->render('categories/update.html.twig', ['form'=>$form->createView()]);
        }

    }