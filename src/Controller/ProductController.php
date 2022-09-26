<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\AddToCartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Manager\CartManager;


/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends AbstractController
{

    /**
     * Helps to see details of a specific product, the form is to add the quantity of product
     * @Route("/product/{id}", name="product.detail")
     */
    public function detail(Product $product, Request $request, CartManager $cartManager ): Response
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('product.detail', ['id' => $product->getId()]);
        }


        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}