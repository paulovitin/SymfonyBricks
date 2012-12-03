<?php
namespace Bricks\siteBundle\Extension;

use Bricks\SiteBundle\Entity\Brick;

/**
 * Twig extensions related to the Brick object
 */
class BrickExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'brick_formatted_tags' => new \Twig_Function_Method($this, 'brickFormattedTags'),
        );
    }
    
    /**
     * print a string of tag titles separated by $separator
     * 
     * @param Brick $brick Brick object
     * @param unknown_type $separator string to separate tag titles
     * @return string
     */
    public function brickFormattedTags(Brick $brick, $separator = ',')
    {
        $output = '';
        
        // number of $brick->getBrickHasTags() array elements
        $brickHasTagsLength = count($brick->getBrickHasTags());
        
        foreach ($brick->getBrickHasTags() as $k => $bht) {
            $tag = $bht->getTag();
            
            if ($tag) {
                // add tag title
                $output .= $tag->getTitle();
                
                if ($k < $brickHasTagsLength-1) {
                    // add separator
                    $output .= $separator.' ';
                }
            }
        }

        return $output;
    }

    public function getName()
    {
        return 'brick_extension';
    }
}
