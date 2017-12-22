<?php

namespace Sigma\AdminBundle\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UpdateEmissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle', TextType::class, array('label' => 'Libelle', 'attr' => array(
            'class' => 'validate', 'required' => true)))
            ->add('description', TextareaType::class, array('label' => 'Description', 'attr' => array(
                'class' => 'materialize-textarea validate', 'required' => true)))
            ->add('logo', FileType::class, array('attr' => array('required' => true)))
            ->add('datedebut',DateTimeType::class,array('widget'=>'single_text','date_format' => 'yyyy-MM-dd  HH:i','html5'=>false,'attr' => array(
                'required' => true,'Placeholder'=>'yyyy-MM-dd  HH:mm'
            )))
            ->add('datefin',DateTimeType::class,array('widget'=>'single_text','date_format' => 'yyyy-MM-dd  HH:i','html5'=>false,'attr' => array(
                'required' => true,'Placeholder'=>'yyyy-MM-dd  HH:mm'
            )))
            ->add('update', SubmitType::class, array('label' => 'Update',
                'attr' => array(
                    'class' => 'btn cyan waves-effect waves-light')));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sigma\AdminBundle\Entity\Emission',
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sigma_adminbundle_emission';
    }


}
