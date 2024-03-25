<?php

namespace App\Form;

use App\Entity\Appointments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('time_at', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client email'
                ],
            ])
            ->add('client_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client name'
                ],
            ])
            ->add('client_email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client email'
                ],
            ])
            ->add('client_phone', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-6 ',
                    'placeholder' => 'Client phone number'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointments::class,
        ]);
    }
}
