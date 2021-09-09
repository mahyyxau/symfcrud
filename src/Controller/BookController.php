<?php
    namespace App\Controller;

    use App\Entity\Book;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;

    class BookController extends Controller {
        /**
         * @Route("/")
         * @Method({"GET"})
         */ 
        public function index(){
            // return new Response('<html><body>Mai</body></html>');
            $books = $this->getDoctrine()->getRepository(Book::class)->findAll();

            return $this->render('books/index.html.twig', ['books' => $books]);
        }

        /**
         * @Route("/book/new")
         * Method({"GET","POST"})
         */
        public function new(Request $request){
            $book = new Book();
            
            $form = $this->createFormBuilder($book)->add('title', TextType::class, ['label'=>'Номын нэр', 'attr'=>['class'=>'form-control']])
                    ->add('author', TextType::class, ['label'=>'Зохиолч', 'required' => false, 'attr' => ['class' => 'form-control']])
                    ->add('save', SubmitType::class, ['label'=>'Бүртгэх','attr'=>['class'=>'btn btn-primary']])->getForm();

            $form ->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $book = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($book);
                $entityManager->flush();

                return $this->redirect('/');
            }

            return $this->render('books/new.html.twig', ['form'=>$form->createView()]);
        }

        
        /**
         * @Route("/book/{id}")
         */
        public function show($id){
            $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

            return $this->render('books/show.html.twig', ['book' => $book]);
        }

        /**
         * @Route("/book/delete/{id}")
         * @Method("DELETE")
         */
        public function delete(Request $request, $id){
            $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();

            $response = new Response();
            $response->send();
        }

        /**
         * @Route("/book/update/{id}")
         * Method({"GET","POST"})
         */
        public function update(Request $request, $id){
            $book = new Book();
            $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
            
            $form = $this->createFormBuilder($book)->add('title', TextType::class, ['label'=>'Номын нэр','attr'=>['class'=>'form-control']])
                    ->add('author', TextType::class, ['label'=>'Зохиолч','required' => false, 'attr' => ['class' => 'form-control']])
                    ->add('save', SubmitType::class, ['label'=>'Засах','attr'=>['class'=>'btn btn-primary']])->getForm();

            $form ->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirect('/');
            }

            return $this->render('books/update.html.twig', ['form'=>$form->createView(),'id'=>$id]);
        }

        /**
         * @Route("/book/save")
         */
        // public function save(){
        //     $entityManager = $this->getDoctrine()->getManager();

        //     $book = new Book();
        //     $book->setTitle('title');
        //     $book->setAuthor('author');

        //     $entityManager->persist($book);
        //     $entityManager->flush();

        //     return new Response('Амжилттай хадгаллаа');
        // }
    }