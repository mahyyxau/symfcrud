<?php
    namespace App\Controller;

    use App\Entity\Book;
    use App\Entity\Category;
    use App\Entity\Author;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Routing\Annotation\Route;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\CallbackTransformer;

    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\Type\DatalistType;
    use Symfony\Component\Validator\Constraints as Assert;

    class BookController extends Controller {
        /**
         * @Route("/")
         * @Method({"GET"})
         */ 
        public function index(){
            $books = $this->getDoctrine()->getRepository(Book::class)->findAll();
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $authors = $this->getDoctrine()->getRepository(Author::class)->findAll();

            return $this->render('books/index.html.twig', [
                'books' => $books,
                'categories' => $categories,
                'authors' => $authors,
            ]);
        }

        /**
         * @Route("/book/new")
         * Method({"GET","POST"})
         */
        public function new(Request $request){
            $book = new Book();
            
            $form = $this->createFormBuilder($book)->add('title', TextType::class, ['label'=>'Номын нэр', 'attr'=>['class'=>'form-control']])
                    ->add('author', EntityType::class, ['label'=>'Зохиолч', 'class' => 'App\Entity\Author', 'attr'=>['class'=>'form-control']])
                    // ->add('category', EntityType::class, ['class' => 'App\Entity\Category'])
                    ->add('category', DatalistType::class, [
                        'label' => 'Төрөл',
                        'class' => 'App\Entity\Category',
                        'attr'=>['class'=>'form-control']
                    ])
                    ->add('publishedat', DateType::class, [
                        'label'=>'Хэвлэсэн огноо',
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd',
                        'attr'=>['class'=>'form-control']
                    ])
                    ->add('price', NumberType::class, ['label'=>'Үнэ', 'attr'=>['class'=>'form-control']])
                    ->add('save', SubmitType::class, ['label'=>'Бүртгэх','attr'=>['class'=>'btn btn-primary']])->getForm();

            $form ->handleRequest($request);
            
            if($form->isSubmitted()){

                $entityManager = $this->getDoctrine()->getManager();
    
                $category = $this->getDoctrine()->getRepository(Category::class)->findBy([
                    'name' => $request->request->get('form')['category']
                ]);
    
                if(!$category){
                    $category = new Category();
                    $category->setName($request->request->get('form')['category']);
                    $entityManager->persist($category);
                    $entityManager->flush();
                    $id = $category->getId();
                }else{
                    $id = $category[0]->getId();
                }

                $book = $form->getData();
                $book->setCategory($this->getDoctrine()->getRepository(Category::class)->find($id));

                $entityManager->persist($book);
                $entityManager->flush();

                return $this->redirect('/');
            }

            return $this->render('books/new.html.twig', ['form'=>$form->createView()]);
        }

        /**
         * @Route("/book/search")
         * Method({"GET","POST"})
         */
        public function searchAction(Request $request){
            $data =$request->request->all();
     
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                    'SELECT b FROM App\Entity\Book b 
                        LEFT JOIN b.category c
                        LEFT JOIN b.author a 
                    WHERE b.title LIKE :data OR c.name LIKE :data OR a.name LIKE :data')
            ->setParameter('data','%'.$data['searchTxt'].'%');
     
            $res = $query->getResult();
         
            return $this->render('books/searchResult.html.twig', ['res' => $res]);
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
                    ->add('author', EntityType::class, ['label'=>'Зохиолч', 'class' => 'App\Entity\Author', 'attr'=>['class'=>'form-control']])
                    ->add('category', EntityType::class, ['class' => 'App\Entity\Category', 'attr'=>['class'=>'form-control']])
                    // ->add('category', DatalistType::class, [
                    //     'label' => 'Төрөл',
                    //     'required'   =>  false,
                    //     'class' => 'App\Entity\Category',
                    //     'choice_label' => function ($category) {
                    //         return $category->getName();
                    //     }
                    // ])
                    // ->addModelTransformer(new CallbackTransformer(
                        // string to null
                        // function ($NumToNull) {
                            
                        //     if(is_numeric($NumToNull->getCategory())){
                        //         return "";
                        //     }
                        // },
                        // function ($NulltoNum) {
                        //     // transform the string back to an array
                        //     if(is_string($stringToNull->getCategory())){
                        //         return "";
                        //     }
                        // }
                    // ))
                    ->add('publishedat', DateType::class, [
                        'label'=>'Хэвлэсэн огноо',
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd',
                        'attr'=>['class'=>'form-control']
                    ])
                    ->add('price', NumberType::class, ['label'=>'Үнэ', 'attr'=>['class'=>'form-control']])
                    ->add('save', SubmitType::class, ['label'=>'Засах','attr'=>['class'=>'btn btn-primary']])->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                // $category = $this->getDoctrine()->getRepository(Category::class)->findBy([
                //     'name' => $request->request->get('form')['category']
                // ]);
    
                // if(!$category){
                //     $category = new Category();
                //     $category->setName($request->request->get('form')['category']);
                //     $entityManager->persist($category);
                //     $entityManager->flush();
                //     $id = $category->getId();
                // }else{
                //     $id = $category[0]->getId();
                // }

                // $book->setCategory($this->getDoctrine()->getRepository(Category::class)->find($id));

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