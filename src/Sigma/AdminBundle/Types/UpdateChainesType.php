<?php

namespace Sigma\AdminBundle\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UpdateChainesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, array('label' => 'Libelle', 'attr' => array(
                'class' => 'validate', 'required' => true)))
            ->add('description', TextareaType::class, array('label' => 'Description', 'attr' => array(
                'class' => 'materialize-textarea validate', 'required' => true)))
            ->add('web', UrlType::class, array('label' => 'Site Web', 'attr' => array(
                'class' => 'validate', 'required' => true)))
            ->add('logo', FileType::class, array('attr' => array('required' => true)))
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
            'data_class' => 'Sigma\AdminBundle\Entity\Chaines',
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sigma_adminbundle_chaines';
    }


}
