<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'datepicker'],
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'datepicker'],
            ])
            ->add('members', EntityType::class, [
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getName();
                },
                'multiple' => true,         // Permet la sélection multiple
                'expanded' => true,         // Affiche les options comme des cases à cocher
                'label' => 'Choisissez les membres du projet',  // Titre du champ
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}