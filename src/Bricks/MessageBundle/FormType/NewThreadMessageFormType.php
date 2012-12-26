<?php

namespace Bricks\MessageBundle\FormType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use FOS\MessageBundle\FormType\NewThreadMessageFormType as BaseFormType;

/**
 *
 */
class NewThreadMessageFormType extends BaseFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient', 'fos_user_username')
            ->add('subject', 'text')
            ->add('body', 'textarea');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // set custom validation group
        $resolver->setDefaults(array(
            'validation_groups' => array('custom')
        ));

        return parent::setDefaultOptions($resolver);
    }
}
