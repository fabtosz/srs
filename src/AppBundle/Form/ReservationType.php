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
 

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*
            ->add('day', ChoiceType::class, array(
                'choices'  => array(
                    'Poniedziałek' => 'Poniedziałek',
                    'Wtorek' => 'Wtorek',
                    'Środa' => 'Środa',
                    'Czwartek' => 'Czwartek',
                    'Piątek' => 'Piątek',
                    'Sobota' => 'Sobota',
                    'Niedziela' => 'Niedziela',
                    ),
                'label' => false,
                'placeholder' => 'Wybierz dzień'
                )
            )
            ->add('timeFrom', ChoiceType::class, array(
                'choices'  => array(
                    '08:00' => '08:00',
                    '09:00' => '09:00',
                    '10:00' => '10:00',
                    '11:00' => '11:00',
                    '12:00' => '12:00',
                    '13:00' => '13:00',
                    '14:00' => '14:00',
                    '15:00' => '15:00',
                    '16:00' => '16:00',
                    '17:00' => '17:00',
                    '18:00' => '18:00',
                    '19:00' => '19:00',
                    '20:00' => '20:00',
                    '21:00' => '21:00'
                    ),
                'label' => false,
                'placeholder' => 'Godzina od'
                )
            )
            ->add('timeTo', ChoiceType::class, array(
                'choices'  => array(
                    '08:00' => '08:00',
                    '09:00' => '09:00',
                    '10:00' => '10:00',
                    '11:00' => '11:00',
                    '12:00' => '12:00',
                    '13:00' => '13:00',
                    '14:00' => '14:00',
                    '15:00' => '15:00',
                    '16:00' => '16:00',
                    '17:00' => '17:00',
                    '18:00' => '18:00',
                    '19:00' => '19:00',
                    '20:00' => '20:00',
                    '21:00' => '21:00'
                    ),
                'label' => false,
                'placeholder' => 'Godzina do'
                )
            )
            */
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
