<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this-> entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(HttpFoundationRequest $request, UserPasswordEncoderInterface $encoder)
    {

        //j'ai injecter userpasswordinterface dans la fonction index 
        // en lui donnant une var qui s'appelle encoder

        $user = new User();
        $form = $this->createForm( RegisterType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            
            $password =$encoder->encodePassword($user,$user->getPassword());
    
     //on a fait appel à la methode encoderpassworinterface qui prend 2paramettre user et password
     //puis  grace à encoderpassword j'ai une chaine qui est  crypter

            $user -> setPassword($password);

            //puis la on reinjecte le pawwsord dans l'objet user 
            $user->setpassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();


        }


        return $this->render('register/index.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
