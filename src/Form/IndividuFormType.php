<?php

namespace App\Form;

use App\Entity\Individu;
use App\Entity\Logement;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndividuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer le nom',
                    'required' => true
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer le prenom',
                    'required' => true
                ]
            ])
            ->add('datenais', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer le date',
                    'required' => true
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telephone',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer le numero',
                    'required' => true
                ]
            ])
            ->add('logement', EntityType::class, [
                'class' => Logement::class,
                'choice_label' => 'lot',
                'attr' => [
                    'class' => 'form-control'
                ]
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
            'data_class' => Individu::class,
            'is_edit' => false
        ]);
    }
}
