<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Form\CartType;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;


/**
 * Class CartController
 * @package App\Controller
 */
class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartManager $cartManager, Request $request): Response
    {
        $cart = $cartManager->getCurrentCart();

        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setUpdatedAt(new \DateTime());
            $cartManager->save($cart);

            return $this->redirectToRoute('cart');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="")
     */
   /*  public function getQty(EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Order::class);

       $order = $repository->count([]);
       dd($order);

        return $this->render('index.html.twig');
    } */
}