<?php

namespace Sigma\AdminBundle\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('question',TextType::class, array('label' => 'Question', 'attr' => array(
            'class' => 'validate', 'required' => true)))
            ->add('save', SubmitType::class, array('label' => 'Save',
                'attr' => array(
                    'class' => 'btn cyan waves-effect waves-light')));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sigma\AdminBundle\Entity\Question',
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sigma_adminbundle_question';
    }


}
