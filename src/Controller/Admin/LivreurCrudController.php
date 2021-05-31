<?php

namespace App\Controller\Admin;

use App\Entity\Livreur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LivreurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livreur::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
