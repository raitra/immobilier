<?php

namespace App\Form;

use App\Entity\Commune;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommuneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_commune', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrer le nom du commune',
                    'required' => true
                ]
            ])
            ->add('distance_agence', NumberType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrer la distance',
                    'required' => true
                ]
            ])
            ->add('nombre_habitant', NumberType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrer le nombre habitant',
                    'required' => true
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['is_edit'] ? 'Modifier' : 'Ajouter',
                'attr' => ['class' => 'form-control mb-3']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commune::class,
            'is_edit' => false
        ]);
    }
}
