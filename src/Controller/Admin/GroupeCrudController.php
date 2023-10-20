<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GroupeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextEditorField::new('info'),
            DateField::new('dateCreation'),
            DateField::new('dateSeparation'),
            ImageField::new('photo')->setUploadedFileNamePattern('[uuid].[extension]')->setBasePath('uploads/groupes')->setUploadDir('public/uploads/groupes'),
            ImageField::new('couverture')->setUploadedFileNamePattern('[uuid].[extension]')->setBasePath('uploads/groupes')->setUploadDir('public/uploads/groupes'),
            AssociationField::new('artistes'),
            AssociationField::new('musiques'),
            AssociationField::new('albums'),
            AssociationField::new('genre'),
            AssociationField::new('admins'),
        ];
    }
    
}
