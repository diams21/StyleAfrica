<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswoController extends AbstractController
{
    /**
     * @Route("/compte/passwomod", name="account_passwo")
     */
    public function index(): Response
    {
        return $this->render('account_passwo/index.html.twig');
    }
}
