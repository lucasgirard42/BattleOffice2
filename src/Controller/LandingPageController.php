<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Form\ClientType;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Client;
use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpClient\HttpClient;
use Stripe\Stripe;

class LandingPageController extends AbstractController
{
    /**
     * @Route("/", name="landing_page")
     * @throws \Exception
     */
    public function index(Request $request)
    {
        // $client = new Client();
        // $product = new Product();
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
       
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
           $order->setPayment($request->request->get('payement'));
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($order);
           $entityManager->flush();
           
            $array =[
                
                    "order"=> [
                      "id"=>$order->getId(),
                      "product"=>$order->getProduct()->getName(),
                      "payment_method"=>$order->getPayment(),
                      "status"=>"WAITING",
                      "client"=> [
                        "firstname"=>$order->getFirstname(),
                        "lastname"=>$order->getLastname(),
                        "email"=> $order->getClient()->getEmail()
                      ],
                      "addresses"=> [
                        "billing"=> [
                          "address_line1"=>$order->getClient()->getAdress(),
                          "address_line2"=>$order->getClient()->getAdditionalAdress(),
                          "city"=> $order->getClient()->getCity(),
                          "zipcode"=> $order->getClient()->getPostal(),
                          "country"=> $order->getclient()->getCountry(),
                          "phone"=> $order->getClient()->getPhone()
                        ],
                        "shipping"=> [
                          "address_line1"=>$order->getAdress(),
                          "address_line2"=>$order->getAdditionalAdress(),
                          "city"=>$order->getCity(),
                          "zipcode"=>$order->getPostal(),
                          "country"=>$order->getCountry(),
                          "phone"=>$order->getClient()->getPhone()
                        ]
                      ]
                    ]
                  
            ];
            
            $ApiRequest = HttpClient::create();
            $response = $ApiRequest->request('POST', 'https://api-commerce.simplon-roanne.com/order',[
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX',
                    'Content-Type' => 'application/json',
                ],
                'body'=> json_encode($array)
            ]);

            
            $content = $response->getContent();
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            $content = $response->toArray();
            // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
            


            return $this->redirectToRoute('payment',["id"=>$order->getId()]);
        }

        return $this->render('landing_page/test.html.twig', [
            'form' => $form->createView(),
            
            // 'products' => $product,
            // 'client' => $client, 
            'order'=> $order, 
          
        ]);

    }
    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', [

        ]);
    }
}