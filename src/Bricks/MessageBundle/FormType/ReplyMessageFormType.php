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
        $resolver->setDefaults(array(
            'validation_groups' => array('custom')
        ));

        return parent::setDefaultOptions($resolver);
    }
}
