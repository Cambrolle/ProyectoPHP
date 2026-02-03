<?php

namespace App\Controller\Admin;

use App\Entity\Competicion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CompeticionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Competicion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Competición'),
            TextField::new('type', 'Tipo'),
            TextField::new('emblem', 'Logo/Emblema'),

            // ESTA ES LA NUEVA COLUMNA
            // 'categories' es el nombre del atributo en tu entidad Competicion
            AssociationField::new('categories', 'Categorías')
                ->setRequired(false) // Permite que sea null
                ->setFormTypeOptions([
                    'by_reference' => false, // IMPORTANTE para relaciones ManyToMany
                ]),
        ];
    }
}
