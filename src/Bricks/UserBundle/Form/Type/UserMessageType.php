<?php

namespace Bricks\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;

class UserMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', 'text')
            /*
            ->add('brick', 'entity', array(
                'class' => 'Bricks\SiteBundle\Entity\Brick',
                'property' => 'id'
            ))
            */
            ->add('recipient_id', 'hidden')
            ->add('brick_id', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'body' => new NotNull(),
            'recipient_id' => new NotNull(),
            'brick_id' => array()
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'bricks_userbundle_usermessagetype';
    }
}
