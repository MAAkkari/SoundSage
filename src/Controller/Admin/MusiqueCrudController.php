<?php

namespace App\Controller\Admin;


use FFMpeg\FFMpeg;
use App\Entity\Groupe;
use App\Entity\Musique;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            AssociationField::new('genres'),
            AssociationField::new('album'),
            AssociationField::new('groupes'),
            ImageField::new('image')->setUploadedFileNamePattern('[uuid].[extension]')
                                    ->setBasePath('uploads/musiques')
                                    ->setUploadDir('public/uploads/musiques'),
            IntegerField::new('duree','durée/s')->hideOnForm(),
            IntegerField::new('nbEcoute')->hideOnForm()                   
        ];
    }
    

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance):void {
        if (!$entityInstance instanceof Musique) return;
        $audio='C:/laragon/www/SoundSage/public/uploads/files/'.$entityInstance->getFichier();
        $ffmpeg = FFMpeg::create();
        $audioInfo = $ffmpeg->open($audio);
        $duration = $audioInfo->getFormat()->get('duration');
        $roundedDuration = floor($duration);
        $entityInstance->setDuree($roundedDuration);
        foreach($entityInstance->getGroupes() as $groupe){
            $groupe->addMusique($entityInstance);  
        }
        parent::persistEntity($entityManager, $entityInstance);
    }
    
    
}
