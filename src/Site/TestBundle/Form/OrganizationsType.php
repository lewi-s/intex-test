<?php

namespace Site\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class OrganizationsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('displayName', TextType::class, array('label' => 'Наименование',))
            ->add('ogrn', TextType::class, array('label' => 'ОГРН',))
            ->add('oktmo', TextType::class, array('label' => 'ОКТМО (внешний идентификатор)',))
            ->add('save', SubmitType::class, array('label' => 'Сохранить и закрыть','attr'=>array('class'=>'add left')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Site\TestBundle\Entity\Organizations',
            'validation_groups' => array('organizationunique')
        ));
    }
    

}
