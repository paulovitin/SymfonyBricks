<?php

namespace Bricks\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'textarea', array(
                'label' => 'Title',
                'error_bubbling' => true
            ))
            ->add('description', 'textarea', array(
                'label' => 'Description'
            ))
            ->add('content', 'textarea', array(
                'label' => 'Content'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bricks\SiteBundle\Entity\Brick'
        ));
    }

    public function getName()
    {
        return 'bricks_userbundle_bricktype';
    }
}
