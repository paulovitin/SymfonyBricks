<?php

namespace Bricks\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Bricks\UserBundle\Form\DataTransformer\TagsToIdsTransformer;

class BrickType extends AbstractType
{
    private $em;
    
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $brick = $builder->getData();
        
        $builder
            ->add('title', 'text', array(
                'error_bubbling' => true
            ))
            ->add('description', 'textarea')
            ->add('canonical_url', 'text')
            ->add('content', 'textarea')
        ;
        
        // data transformer for brickHasTags field
        $transformer = new TagsToIdsTransformer($this->em, $brick);
        
        // add brickHasTags field, with Data Transformer
        $builder->add(
            $builder->create('brickHasTags', 'hidden')
                ->prependNormTransformer($transformer)
        );
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
