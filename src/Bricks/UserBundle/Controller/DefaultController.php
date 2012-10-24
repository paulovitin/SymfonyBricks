<?php

namespace Bricks\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/user")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/dashboard", name="user_dashboard")
     * @Template()
     */
    public function dashboardAction()
    {
        return array();
    }
}
