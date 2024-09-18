<?php

namespace App\Form;

use App\Entity\Logement;
use App\Entity\Quartier;
use App\Entity\TypeLogement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lot', TextType::class, [
                'label' => 'Lot',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer le lot du logement',
                    'required' => true
                ]
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer la rue',
                    'required' => true
                ]
            ])
            ->add('superficie', NumberType::class, [
                'label' => 'Superficie',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer le superficie',
                    'required' => true
                ]
            ])
            ->add('loyer', NumberType::class, [
                'label' => 'Loyer',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer le loyer',
                    'required' => true
                ]
            ])
            ->add('quartier', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'    
                ],
                'class' => Quartier::class,
                'choice_label' => 'libelleQuartier',
            ])
            ->add('typeLog', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => TypeLogement::class,
                'choice_label' => 'type',
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['is_edit'] ? 'Modifier' : 'Ajouter',
                'attr' => ['class' => 'form-control mt-3']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
            'is_edit' => false
        ]);
    }
}
