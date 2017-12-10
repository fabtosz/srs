<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
 

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                /*
                'widget' => 'single_text',
                'placeholder' => 'Wybierz dzień'
                */
                'label' => 'Tytuł rezerwacji',
            ))
            ->add('date', DateType::class, array(
                /*
                'widget' => 'single_text',
                'placeholder' => 'Wybierz dzień'
                */
                'label' => 'Wybierz dzień',
                'placeholder' => 'Wybierz dzień',
                'widget' => 'single_text',
                'html5' => true
            ))
            ->add('hour', TimeType::class, array(
                'label' => 'Wybierz godzinę',
                'with_minutes' => false,
                'hours' => range(8,20)
            ))
            ->add('duration', IntegerType::class, array(
                'label' => 'Czas trwania',
                'attr' => array('min' => 1, 'max' => 13),
                'data' => 1
            ))
            ->add('genre', ChoiceType::class, array(
                'label' => 'Rodzaj zajęć',
                'choices'  => array(
                    'Wykład' => 'Wykład',
                    'Ćwiczenia' => 'Ćwiczenia',
                    'Próba' => 'Próba',
                    'Orkiestra' => 'Orkiestra',
                    'Egzamin' => 'Egzamin',
                ),
            ))
            ->add('overload', IntegerType::class, array(
                'label' => 'Obciążenie',
                'attr' => array('min' => 25, 'max' => 300),
                'data' => 25
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reservation';
    }


}
