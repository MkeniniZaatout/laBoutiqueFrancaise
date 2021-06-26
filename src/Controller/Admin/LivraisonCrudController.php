<?php

namespace App\Controller\Admin;

use App\Entity\Livraison;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class LivraisonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livraison::class;
    }

    public function configureActions(Actions $actions): Actions {

        return $actions->add('index', 'detail');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passé le'),
            TextField::new('user.fullName', 'Nom'),
            MoneyField::new('total')->setCurrency('EUR'),
            // MoneyType::new('total')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Payé')
        ];
    }
    
}
