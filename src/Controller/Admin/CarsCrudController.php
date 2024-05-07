<?php

namespace App\Controller\Admin;

use App\Entity\Cars;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cars::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('brand'),
            TextField::new('model'),
            IntegerField::new('year'),
            IntegerField::new('mileage'),
            TextField::new('fuel'),
            TextField::new('type'),
            TextField::new('gearBox'),
            IntegerField::new('price'),
            TextEditorField::new('description'),
            TextField::new('picture')
        ];
    }
    
}
