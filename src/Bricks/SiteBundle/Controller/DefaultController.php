<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bricks = $em->getRepository('BricksSiteBundle:Brick')->findBy(array('published' => true));
        
        return array(
            'bricks' => $bricks
        );
    }
    
    /**
     * @Route("/contribute", name="contribute")
     * @Template()
     */
    public function contributeAction()
    {
        return array();
    }
}
