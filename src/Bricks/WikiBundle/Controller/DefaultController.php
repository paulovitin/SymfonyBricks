<?php

namespace Bricks\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Default controller.
 */
class DefaultController extends Controller
{
    /**
     * Wiki index
     *
     * @Route("/", name="wiki_homepage")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('wiki_symfonybricks'));
    }

    /**
     * Wiki: symfonybricks.com
     *
     * @Route("/symfonybricks", name="wiki_symfonybricks")
     * @Template()
     */
    public function symfonyBricksAction()
    {
        return array();
    }
}
