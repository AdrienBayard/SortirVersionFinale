<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    const USERS_BASE_PATH = 'upload/images/users';
    const USERS_UPLOAD_DIR = 'public/upload/images/users';

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('pseudo'),
            TextField::new('nom'),
            TextField::new('prenom'),
            IdField::new('telephone'),
            TextField::new('mail'),
            TextField::new('password'),
            ImageField::new('photo')
                ->setBasePath(self::USERS_BASE_PATH)
                ->setUploadDir(self::USERS_UPLOAD_DIR),
            BooleanField::new('actif'),
            BooleanField::new('premiereconnexion'),
            AssociationField::new('site'),

        ];
    }

}
