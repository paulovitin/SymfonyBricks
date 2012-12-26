<?php

namespace Bricks\MessageBundle\FormType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use FOS\MessageBundle\FormType\ReplyMessageFormType as BaseFormType;

/**
 *
 */
class ReplyMessageFormType extends BaseFormType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // set custom validation group
        $resolver->setDefaults(array(
            'validation_groups' => array('custom')
        ));

        return parent::setDefaultOptions($resolver);
    }
}
