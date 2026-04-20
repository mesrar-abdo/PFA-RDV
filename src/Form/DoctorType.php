<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class DoctorType extends AbstractType
{
    // src/Form/DoctorType.php
public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('specialty', TextType::class, [
            'attr' => ['placeholder' => 'e.g. Cardiologist, Dentist...']
        ])
        ->add('officeAddress', TextType::class)
        ->add('biography', TextareaType::class, [
            'attr' => ['rows' => 5]
        ])
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
        ]);
    }
}
