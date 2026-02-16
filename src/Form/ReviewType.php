<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stars', ChoiceType::class, [
                'label' => 'Valoración',
                'choices' => [
                    '5 Estrellas' => 5,
                    '4 Estrellas' => 4,
                    '3 Estrellas' => 3,
                    '2 Estrellas' => 2,
                    '1 Estrella' => 1,
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Tu comentario',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
