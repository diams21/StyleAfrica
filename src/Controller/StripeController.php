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
        $product_for_stripe=[];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach ($cart->getFull() as $product){

            $product_for_stripe[] =[

                'price_data' =>[
                    'currency'=>'eur',
                    'unit_amount'=>$product['product']->getPrix(),
                        'product_data'=>[
                            'name'=>$product['product']->getName(),
                            'images'=> [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                    ],
                    ],
                'quantity' => $product['quantity'],
             

           ];

        }

        Stripe::setApiKey  ('sk_test_51L2XrgFn8EmCT5qnb7eyvVIkgjyh7kM2n1EIWxY6zLNgptBr42luUIgfw6trnJfnjmsfWDmLSMs1T9RemlgGeY3500fKMV8UuJ');
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

$checkout_session = Session::create([
  'payment_method_types' => ['card'], 
  'line_items' => [$product_for_stripe],
'mode' => 'payment',
'success_url' => $YOUR_DOMAIN . '/success.html',
'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
]);

$response = new JsonResponse(['id'=>$checkout_session->id]);
return $response;

    }
}
