<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bricks\SiteBundle\Entity\Brick;
use Bricks\UserBundle\Form\BrickType;

/**
 * Brick controller.
 *
 * @Route("/brick")
 */
class BrickController extends Controller
{
    /**
     * Lists all Brick entities.
     *
     * @Route("/", name="brick")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BricksSiteBundle:Brick')->findBy(array('published' => true));

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Display a page to inform that a brick is not published
     *
     * @Route("/not-published", name="brick_not_published")
     * @Template()
     */
    public function notPublishedAction()
    {
        return array();
    }

    /**
     * Show a brick
     *
     * @Route("/{id}/{slug}", name="brick_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }
        
        /**
         * if the brick is not published, return a temporary redirection
         */
        if (!$entity->getPublished()) {
            return $this->redirect($this->generateUrl('brick_not_published'), 307);
        }
        
        return array(
            'brick' => $entity
        );
    }
}
