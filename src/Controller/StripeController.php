<?php

namespace App\Controller;

use App\classe\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;
use Stripe\FinancialConnections\Session as FinancialConnectionsSession;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;
use Symfony\Component\Validator\Constraints\Json;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session", name="stripe_create_session")
     */
    public function index(Cart $cart)
    {
        $products_for_stripe=[];//j'initialise un tab vide 
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach ($cart->getFull() as $product){// on est aller sur le panier $cart pour voir les produits quil ya et demander pour chaque produit de mon panier tu cree une orderdetails

              //puis on definis le different products que je souhaite envoyer à stripe 
                //on rajoute une entrée avec tout mes lines items quon va recuperer dans orderdetails

            $products_for_stripe[]=[
                'price_data'=>[
                    'currency'=>'eur',
                    'unit_amount'=> $product['product']->getPrix(),
                    'product_data'=>[
                        'name'=>$product['product']->getName(),
                        'images'=> [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                    ],
                ],
                
                    'quantity' => $product['quantity'],

            ];

        }

        Stripe::setApiKey('sk_test_51L2XrgFn8EmCT5qnb7eyvVIkgjyh7kM2n1EIWxY6zLNgptBr42luUIgfw6trnJfnjmsfWDmLSMs1T9RemlgGeY3500fKMV8UuJ');//initialisation de la clé public de l'API de stripe 
        header('Content-Type: application/json');
       
       
       $checkout_session = Session::create([//le retour de L'API sur la checkout session
        //avec toutes les parmatres que lui ai demander

           'payment_method_types' => ['card'],
         'line_items' => [
           $products_for_stripe
         ],
         'mode' => 'payment',
         'success_url' => $YOUR_DOMAIN . '/success.html',
         'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
       ]);

       $response = new JsonResponse(['id'=>$checkout_session->id]);
       return  $response ;//LA REPONSE EN JSON 
    }
}
