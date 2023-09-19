<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Personne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut', DateType::class, [
                'label' => "Date d'emprunt",
                'widget' => 'single_text'
            ])
            ->add('date_retour', DateType::class, [
                'label' => "Date de Retour",
                'widget' => 'single_text'
            ])
            ->add('personne', EntityType::class, [
                'label' =>'Personne',
                'class' => Personne::class,
                'placeholder'=> 'Choisir une personne'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
