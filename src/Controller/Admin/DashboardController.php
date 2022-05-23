<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Style Africa');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Mes Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Mes commande', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Mes Categories', 'fas fa-solid fa-clipboard-list', Category::class);
        yield MenuItem::linkToCrud('Mes Produits', 'fas fa-solid fa-tags', Product::class);
        yield MenuItem::linkToCrud('Header', ' fas fa-solid fa-desktop', Header::class);
        yield MenuItem::linkToCrud('Transporteurs', 'fas fa-solid fa-truck',Carrier::class);
    }
}
