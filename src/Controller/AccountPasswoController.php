<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswoController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this-> entityManager = $entityManager;
    }


    /**
     * @Route("/compte/passwomod", name="account_passwo")
     */
     public function index(Request $request,UserPasswordEncoderInterface $encoder)

    {
        $notification=null;
        $user=$this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);


    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $old_pwd= $form->get('old_password')->getData();


            if($encoder->isPasswordValid($user,$old_pwd)){

                $new_pwd= $form->get('new_password')->getData();
                $password =$encoder->encodePassword($user,$new_pwd) ;
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification="votre mot de passe à bien été mis à jour.";

            }
            else{
                $notification="Votre mot de passe actuel n'est pas le bon";
            }

        }

        return $this->render('account_passwo/index.html.twig',[
            'form'=>$form->createView(), 
            'notification'=> $notification
        
        ]);
    }
}
