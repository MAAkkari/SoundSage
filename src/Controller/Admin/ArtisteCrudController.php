<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArtisteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Artiste::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextEditorField::new('info'),
            ImageField::new('photo')->setUploadedFileNamePattern('[uuid].[extension]')->setBasePath('uploads/artistes')
                                    ->setUploadDir('public/uploads/artistes'),
            DateField::new('dateNaissance'),
            DateField::new('dateMort'),
            AssociationField::new('groupes'),
        ];
    }
    
}
