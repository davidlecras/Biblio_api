<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Nationality;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Belle Bibliothèque');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Auteurs', 'fas fa-user', Auteur::class);
        yield MenuItem::linkToCrud('Editeurs', 'fas fa-tags', Editeur::class);
        yield MenuItem::linkToCrud('Genre', 'fas fa-list', Genre::class);
        yield MenuItem::linkToCrud('Livre', 'fas fa-book', Livre::class);
        yield MenuItem::linkToCrud('Nationalité', 'fas fa-globe-europe', Nationality::class);
    }
}
