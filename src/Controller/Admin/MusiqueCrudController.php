<?php

namespace App\Controller\Admin;

use autoload;
use App\Entity\Musique;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class MusiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Musique::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('fichier')
                ->setFormType(FileUploadType::class),
            TextField::new('nom','titre'),
            DateField::new('dateCreation'),
            ImageField::new('image')->setUploadedFileNamePattern('[uuid].[extension]')->setBasePath('uploads/musiques')->setUploadDir('public/uploads/musiques')
            
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance):void {

        if (!$entityInstance instanceof Musique) return;
        $audio='public/uploads/files/'.$entityInstance->getFichier();
        $getID3 = new getID3();
        $audioInfo = $getID3->analyze($audio);
        $durationInSeconds = $audioInfo['playtime_seconds'];
        dd($durationInSeconds);
        
    }

    
}
