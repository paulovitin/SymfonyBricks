<?php

namespace Bricks\UserBundle\Controller;

use Bricks\SiteBundle\Entity\Brick;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Bricks\UserBundle\Form\Type\UserMessageType;

/**
 * UserMessage controller.
 *
 * @Route("/user/usermessage")
 */
class UserMessageController extends Controller
{
    /**
     * Sends a message
     *
     * @Route("/ajax-send-from-brick", name="usermessage_ajax_send_from_brick")
     * @Template()
     */
    public function ajaxSendFromBrickAction()
    {
        $form = $this->createForm(new UserMessageType());
        
        // initialize json array for response
        $json = array();
        
        // bind the request
        $form->bind($this->getRequest());
        
        // send a message
        if ($form->isValid()) {
            
            // retrieve form data
            $data = $form->getData();
            
            /*
             * set the sender
             */
            $sender = $this->container->get('security.context')->getToken()->getUser();
            if (!$sender) {
                $json = array('error' => 'It was impossibile to set a sender');
                break;
            }
                
            /*
             * set the recipient
             */
            $userManager = $this->container->get('fos_user.user_manager');
            $recipient = $userManager->findUserBy(array('id' => $data['recipient_id']));
            if (!$recipient) {
                $json = array('error' => 'It was impossibile to set a recipient');
                break;
            }
            
            /*
             * set the Brick
             */
            $em = $this->getDoctrine()->getManager();
            $brick = $em->getRepository('BricksSiteBundle:Brick')->find($data['brick_id']);
            if (!$brick) {
                $json = array('error' => 'It was impossibile to set the corresponding Brick');
                break;
            }
                
            // UserMessage composer
            $composer = $this->container->get('fos_message.composer');
            
            // compose a new message, creating a new thread
            $message = $composer->newThread()
                ->setSender($sender)
                ->addRecipient($recipient)
                ->setSubject("Talking about brick \"{$brick->getTitle()}\"")
                ->setBody($data['body'])
                ->getMessage()
            ;
            
            $sender = $this->container->get('fos_message.sender');
            
            if ($sender->send($message)) {
                return new Response(json_encode($json), 200, array('Content-Type'=>'application/json'));
            }
        }
        
        return new Response(json_encode($json), 400, array('Content-Type'=>'application/json'));
    }
}
