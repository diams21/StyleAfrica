<?php

namespace App\Controller;

use App\Classe\Search;

use App\Entity\Product;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController

{
    

        private $entityManager;
        public function __construct(EntityManagerInterface $entityManager){
            $this-> entityManager = $entityManager;
        }
     

    /**
     * @Route("/nos-produits", name="products")
     */

    public function index(Request $request)
    {

        

        $search= new Search();
        //nouvelle instance search
        $form= $this->createForm(SearchType::class, $search);
// que je passse a mon formulaire pour qu'il le construisent
        $form->handleRequest($request);
        //pour ecouter la requette pour savoir si le formulaire à ete soumis
        if ($form->isSubmitted() && $form->isValid()){

            $products= $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            // grace à entity manager on est aller chercher le produits dans product

        }else{

            $products= $this->entityManager->getRepository(Product::class)->findAll();
        }
       

        return $this->render('product/index.html.twig', [
          'products'=> $products,
          'form' =>$form->createView()
          // nous avons passer le formulaire à la vue twig
        ]);
    }


     /**
     * @Route("/produit/{slug}", name="product")
     */

    public function show($slug): Response
    {

        $product= $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $products=$this->entityManager->getRepository(Product::class)->findByIsBest(1);

            if (!$product){

                return $this->redirectToRoute('products');
            }


        return $this->render('product/show.html.twig', [
          'product'=> $product,
          'products'=> $products
        ]);
    }
}
