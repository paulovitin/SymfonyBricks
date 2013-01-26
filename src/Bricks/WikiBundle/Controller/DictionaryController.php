<?php

namespace Bricks\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Dictionary controller.
 *
 * @Route("/dictionary")
 */
class DictionaryController extends Controller
{
    /**
     * Wiki -> dictionary: brick
     *
     * @Route("/brick", name="wiki_dictionary_brick")
     * @Template()
     */
    public function brickAction()
    {
        return array();
    }
}
