<?php

namespace Site\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UsersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('post')
            ->add('lastname', TextType::class, array('label' => 'Фамилия',))
            ->add('firstname', TextType::class, array('label' => 'Имя',))
            ->add('middlename', TextType::class, array('label' => 'Отчество',))
            ->add('datebirth',  DateType::class, array( 'widget' => 'single_text','format' => 'yyyy-MM-dd','label' => 'Дата рождения','invalid_message'=>"Дата рождения должна быть формата: ГГГГ-ММ-ДД"))
            ->add('inn', TextType::class, array('label' => 'ИНН',))
            ->add('snils', TextType::class, array('label' => 'СНИЛС',))
            ->add('orgid', EntityType::class, array('class' => 'SiteTestBundle:Organizations','choice_label' => 'displayName','label' => 'Организация','attr'=>array('required'  => true)))
            ->add('save', SubmitType::class, array('label' => 'Сохранить и закрыть','attr'=>array('class'=>'add left')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Site\TestBundle\Entity\Users',
            'validation_groups' => array('userunique')
        ));
    }
    
}
