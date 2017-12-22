<?php

namespace Sigma\AdminBundle\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateNotificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', TextType::class, array('label' => 'Contenu', 'attr' => array(
            'class' => 'validate', 'required' => true)))
            ->add('point', NumberType::class, array('label' => 'Contenu', 'attr' => array(
                'class' => 'validate', 'required' => true)))
            ->add('sujet', ChoiceType::class, array(
                'choices' => array(
                    "Emission Tv" => "Emission Tv",
                    "Emission Radio" => "Emission Radio"),
                'multiple' => false,
                'data' => 1
            ,'attr' => array(
                    'class' => 'validate', 'required' => true
                )))
            ->add('update', SubmitType::class, array('label' => 'Update',
            'attr' => array(
                'class' => 'btn cyan waves-effect waves-light')));;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sigma\AdminBundle\Entity\Notification',
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sigma_adminbundle_notification';
    }


}
