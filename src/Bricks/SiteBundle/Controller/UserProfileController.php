<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/profile")
 */
class UserProfileController extends Controller
{
    /**
     * @Route("/show/{username}", name="userprofile_show")
     * @Template()
     */
    public function showAction($username)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        
        $user = $userManager->findUserByUsername($username);

        if (!$user) {
            throw $this->createNotFoundException("Unable to find User \"{$username}\".");
        }
        
        
        $em = $this->getDoctrine()->getManager();
        $publishedBricks = $em->getRepository('BricksSiteBundle:Brick')->findBy(array(
            'user'        => $user,
            'published'   => true
        ));

        return array(
            'user'               => $user,
            'publishedBricks'    => $publishedBricks
        );
    }
}
