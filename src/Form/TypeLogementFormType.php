<?php

namespace App\Form;

use App\Entity\TypeLogement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeLogementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'placeholder' => 'Entrer le type de commune',
                'required' => true
            ]
        ])
        ->add('charges', NumberType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'placeholder' => 'Entrer la charge',
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
            'data_class' => TypeLogement::class,
            'is_edit' => false
        ]);
    }
}
