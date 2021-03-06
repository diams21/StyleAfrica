<?php

namespace App\Controller;

use App\classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\DateTimeTzImmutableType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;

class OrderController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
    $this-> entityManager = $entityManager;
   }
  

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart,Request $request)//injection de dependance

    {
        if(!$this->getUser()->getAddresses()->getValues()){

            return $this->redirectToRoute('account_address_add');// si tu ne trouve pas d'address tu retourne sur la page pour ajouter une adrresse
        }

        $form=$this->createForm(OrderType::class, null,['user'=>$this->getUser()]);



        return $this->render('order/index.html.twig',[
            'form'=>$form->createView(),
            'cart'=> $cart->getFull()
        
        ]);
    }

        /**
       * @Route("/commande/recapitulatif ", name="order_recap", methods ={"POST"})
     */
    public function add(Cart $cart,Request $request)//injection de dependance

    {

        $form=$this->createForm(OrderType::class, null,['user'=>$this->getUser()]);

        $form->handleRequest($request);//demande d'ecouter la request

        if ($form->isSubmitted() && $form->isValid()){//si le form est sumis et est valide

           //enregistrer ma commande Order()
                  
           $date = new DateTimeImmutable( );//initialiser la date 
           $carriers =$form->get('carriers')->getData();// recuperer les donn??es de carriers
           $delivery =$form->get('addresses')->getData();
           $delivery_content =$delivery->getFirstname().''.$delivery->getLastname();
           $delivery_content .='<br/> '. $delivery->getPhone();

           if ( $delivery->getCompany()){

                $delivery_content .='<br/> '. $delivery->getCompany();
           }

           $delivery_content .='<br/> '. $delivery->getAddress();
           $delivery_content .='<br/> '. $delivery->getPostal().''. $delivery->getCity();
           $delivery_content .='<br/> '. $delivery->getCountry();

          

           //Enregistrer ma commande (Order)
           $order= new Order();
           $reference=$date->format('dmY').'-'.uniqid(); 
           $order->setReference($reference);
           $order->setUser($this->getUser());
           $order->setCreateAt($date);
           $order->setCarrierName($carriers->getName());
           $order->setCarrierPrice($carriers->getPrice());
           $order->setDelivery($delivery_content);
           $order->setIsPaid(0);
           $this-> entityManager->persist($order);

         
           
           foreach ($cart->getFull() as $product){// on est aller sur le panier $cart pour voir les produits quil ya et demander pour chaque produit de mon panier tu cree une orderdetails

            $orderDetails = new OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrix());
            $orderDetails->setTotal($product['product']->getPrix()*$product['quantity']);
            $this-> entityManager->persist($orderDetails);

        }
       
       
         // $this-> entityManager->flush();


       

            return $this->render('order/add.html.twig',[
         
                'cart'=> $cart->getFull(),// je passe mon panier ?? twig
                'carrier'=> $carriers,// je passe mon transporteur ?? twig
                'delivery'=>  $delivery_content, // je passe mon addresse de livraison ?? twig
               'reference'=>$order->getReference() //je passe ma reference ?? twig
             
            ]);
        }
        return $this->redirectToRoute('cart');
    }

} 




