<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nombre de la Categoría'),
            // Usamos TextField para la imagen si es una URL de texto,
            // o ImageField si vas a subir archivos.
            TextField::new('image', 'URL de la Imagen'),

            // Esto permite ver qué competiciones tienen esta categoría desde aquí
            AssociationField::new('competiciones', 'Competiciones Asociadas')
                ->hideOnForm(),
        ];
    }
}
