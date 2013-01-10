<?php
namespace Bricks\siteBundle\Extension;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DomCrawler\Crawler;
use Bricks\SiteBundle\Entity\Brick;

/**
 * Twig extensions related to the Brick object
 */
class BrickExtension extends \Twig_Extension
{
    private $router;
    private $tiwg;
   
    public function __construct(Router $router, $twig)
    {
        $this->router = $router;
        $this->twig = $twig;
    }
    
    public function getFunctions()
    {
        return array(
            'brick_formatted_tags' => new \Twig_Function_Method($this, 'brickFormattedTags'),
            'brick_highlight_code' => new \Twig_Function_Method($this, 'brickHighlightCode'),
        );
    }
    
    /**
     * print a string of Brick tag titles separated by $separator
     * 
     * @param Brick $brick Brick object
     * @param string $separator string to separate tag titles
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
                $output .= <<<EOD
<a href="{$this->router->generate('brick_search', array('tag' => $tag->getSlug()))}">{$tag->getTitle()}</a>
EOD;
                
                // if not last iteration
                if ($k < $brickHasTagsLength-1) {
                    // add separator
                    $output .= $separator.' ';
                }
            }
        }

        return $output;
    }

    /**
     * Highlight the content of <pre> tags of $source string via HighlightBundle
     *
     * @param $source string containing <pre> tags to be highlighted
     * @return string string containing <pre> tags with highlighted code
     */
    public function brickHighlightCode($source)
    {
        $highlighter = $this->twig->getExtension('twig.extension.highlight');

        // create a DOMDocument to replace the content of <pre> tags with highlighted code
        $doc = new \DOMDocument();

        // load $source string as html
        $doc->loadHTML($source);

        $xpath = new \DOMXPath($doc);

        // replace the content of <pre> tags with highlighted code
        foreach ($xpath->query('//pre') as $node) {

            $highlightedSource = ($highlighter->highlight(trim($node->firstChild->nodeValue), 'html'));

            $highlightedDomFragment = $doc->createDocumentFragment();
            $highlightedDomFragment->appendXML("<![CDATA[{$highlightedSource}]]>");

            $node->parentNode->replaceChild($highlightedDomFragment, $node);
        }

        return $doc->saveHTML();
    }

    public function getName()
    {
        return 'brick_extension';
    }
}
