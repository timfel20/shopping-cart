<?php


namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

     /**
         * @Route("/create", name="create_article")
         * Method({GET, POST})
         */
        public function store(Request $request)
        {

        $product = new Product();
        $form = $this->createFormBuilder($product)
        ->add('name', textType::class, array('attr'=> array(
            'class' => 'form-control mb-2',
            
        )))
        ->add('description', textType::class, array('attr'=> array(
            'class' => 'form-control mb-2'
        )))
        ->add('price', textType::class, array('attr'=> array(
            'class' => 'form-control'
        )))
        ->add('save', submitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary mt-3')
        ))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('notice','Saved successfully');
    
            return $this->redirectToRoute('home');

        }
    
            return $this->render('create/create.html.twig', array(
            'form' => $form->createView()
            ));
    }


}