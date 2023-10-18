<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\Genre;
use App\Entity\Groupe;
use App\Entity\Artiste;
use App\Entity\Musique;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{   
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {
    
}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(AlbumCrudController::class)->generateUrl();
        return $this->redirect($url); 
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SoundSage');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::submenu('Albums','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('ajouter un Album','fas fa-plus', Album::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('liste des Albums','fas fa-eye', Album::class)
        ]);

        yield MenuItem::submenu('Artistes','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('ajouter un Artiste','fas fa-plus', Artiste::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('liste des Artistes','fas fa-eye', Artiste::class)
        ]);

        yield MenuItem::submenu('Genres','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('ajouter un Genre','fas fa-plus', Genre::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('liste des Genres','fas fa-eye', Genre::class)
        ]);

        yield MenuItem::submenu('Musiques','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('ajouter un Musique','fas fa-plus', Musique::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('liste des Musiques','fas fa-eye', Musique::class)
        ]);

        yield MenuItem::submenu('Groupes','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('ajouter un Groupe','fas fa-plus', Groupe::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('liste des Groupes','fas fa-eye', Groupe::class)
        ]);
        
        yield MenuItem::submenu('Users','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('ajouter un User','fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('liste des Users','fas fa-eye', User::class)
        ]);     
    }
}
