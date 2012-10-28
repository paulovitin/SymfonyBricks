<?php

namespace Bricks\UserBundle\Controller;

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
 * @Route("/user/brick")
 */
class BrickController extends Controller
{
    /**
     * Lists all Brick entities.
     *
     * @Route("/", name="user_brick")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BricksSiteBundle:Brick')->findBy(array('user' => $user->getId()));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Brick entity.
     *
     * @Route("/new", name="user_brick_new")
     * @Template("BricksUserBundle:Brick:edit.html.twig")
     */
    public function newAction()
    {
        $entity = new Brick();
        $form   = $this->createForm(new BrickType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Brick entity.
     *
     * @Route("/create", name="user_brick_create")
     * @Method("POST")
     * @Template("BricksUserBundle:Brick:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Brick();
        $form = $this->createForm(new BrickType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            // set the user
            $user = $this->container->get('security.context')->getToken()->getUser();
            $entity->setUser($user);
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_brick_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Brick entity.
     *
     * @Route("/{id}/edit", name="user_brick_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }
        
        // check user permissions on this brick
        $this->checkUserCanEditBrick($entity);
        
        $editForm = $this->createForm(new BrickType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Brick entity.
     *
     * @Route("/{id}/update", name="user_brick_update")
     * @Method("POST")
     * @Template("BricksSiteBundle:Brick:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }
        
        // check user permissions on this brick
        $this->checkUserCanEditBrick($entity);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BrickType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_brick_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
    
    /**
     * Return the markdown formattation of an input text
     * 
     * \@TODO: refactor this function to some general utility class
     * 
     * @Route("/_render-markdown", name="_user_brick_renderMarkdown")
     * @Template()
     * @method("POST")
     * 
     * @param unknown_type $content
     */
    public function _renderMarkdownAction()
    {
        $content = $this->getRequest()->get('content');
        
        return array('content' => $content);
    }

    /**
     * Deletes a Brick entity.
     *
     * @Route("/{id}/delete", name="user_brick_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Brick entity.');
            }

            // check user permissions on this brick
            $this->checkUserCanEditBrick($entity);
            
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user_brick'));
    }
    
    /**
     * returns a partial template to delete a brick
     * 
     * @Template
     */
    public function _deleteFormAction($id)
    {
        return array(
            'form' =>$this->createDeleteForm($id)->createView(),
            'id' => $id
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * check if a uer can edit a brick
     * 
     * @param unknown_type $brick
     * @throws AccessDeniedException
     */
    private function checkUserCanEditBrick($brick)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        if (!$brick->getUser() || $brick->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('Yo are not allowed to access this content');
        }
    }
    
    /**
     * Toggle the "published" state of a brick
     * 
     * @Route("/toggle-published/{id}", name="user_brick_toggle_published")
     * 
     * @param unknown_type $id
     */
    public function togglePublishedAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }
        
        // check user permissions on this brick
        $this->checkUserCanEditBrick($entity);

        // toggle "published"
        $entity->setPublished(!$entity->getPublished());
        
        // saves the entity
        $em->persist($entity);
        $em->flush();
        
        return $this->redirect($this->generateUrl('user_brick'));
    }
}
