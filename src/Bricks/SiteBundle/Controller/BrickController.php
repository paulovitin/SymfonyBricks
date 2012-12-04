<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Bricks\SiteBundle\Entity\Brick;
use Bricks\UserBundle\Form\Type\BrickType;
use Bricks\SiteBundle\Entity\UserStarsBrick;

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
            'bricks' => $entities,
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
     * @Route("/{id}/{slug}", name="brick_show", requirements={"id"="\d+"})
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

    /**
     * Search bricks
     *
     * @Route("/search", name="brick_search")
     * @Template()
     */
    public function searchAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        // search for bricks
        $entities = $em->getRepository('BricksSiteBundle:Brick')->search(array(
            'q' => $this->getRequest()->get('q'),
            'tag_slug' => $this->getRequest()->get('tag')
        ));
        
        return array(
            'entities' => $entities
        );
    }
    
    /**
     * Toggle the "star" of a brick from a user
     * 
     * With this action, a user can "star" or "un-star" a brick (like marking it as favorite)
     * 
     * @Route("/toggle-star/{brick_id}", name="user_brick_toggle_star", options={"expose"=true})
     * #TODO: security access
     * 
     * @param unknown_type $id
     */
    public function toggleStarAction($brick_id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($brick_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }
        
        // toggle "star" state
        if ($user->isStarringBrick($entity)) {
            
            $userStarsBrick = $em->getRepository('BricksSiteBundle:UserStarsBrick')->findOneBy(array(
                'brick' =>    $entity->getId(),
                'user' =>     $user->getId()
            ));
            
            $em->remove($userStarsBrick);
            $em->flush();
            
            $jsonResponse = array('action' => 'unstarred');
            
        } else {
            $userStarsBrick = new UserStarsBrick();
            
            $userStarsBrick->setUser($user);
            $userStarsBrick->setBrick($entity);
            
            $user->addUserStarsBrick($userStarsBrick);
            
            $userManager = $this->container->get('fos_user.user_manager');
            $userManager->updateUser($user);
            
            $jsonResponse = array('action' => 'starred');
        }
        
        
        /*
        if ($entity->getPublished()) {
            $this->get('session')->setFlash('success', 'alert.brick.togglePublished.published');
        } else {
            $this->get('session')->setFlash('information', 'alert.brick.togglePublished.unpublished');
        }
        */
        
        return new Response(json_encode($jsonResponse), 200, array('Content-Type'=>'application/json'));
    }
}
