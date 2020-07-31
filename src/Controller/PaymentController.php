<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/{id}", name="payment")
     */
    public function index(Order $order): Response
    {
        $price = $order->getProduct()->getPrice();
        $email = $order->getClient()->getEmail();

        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
            'price'=>$price,
            'email'=>$email,
            'order'=>$order,
            
        ]);
    }

    /**
     * @Route("/paymentNew/{id}", name="paymentNew")
     */
    public function paymentNew(Order $order)
    {
        \Stripe\Stripe::setApiKey('sk_test_51HAuy1HoQX1zvQvKhsmKuGOBg9p9WCYHKCrIb2oaPrRFTavza3PaCoFDhFP5w5ulbauJLOk3qC3sRojbKVoQ6QAH00kDZRzf09');

            try{ 
            // Token is created using Stripe Checkout or Elements!
            // Get the payment token ID submitted by the form:
            //  $token = $request->request->get('stripeToken'); /

            $charge = \Stripe\PaymentIntent::create([
            'price' => intval($order->getProduct()->getPrice())*100,
            'currency' => 'eur',
            'description' => 'Example charge', 
            /* 'source' => $token, */

            ]);

            } catch (\Exception $e) {
               

            } 


            return $this->render('landing_page/confirmation.html.twig', [

            ]);
    }

}

