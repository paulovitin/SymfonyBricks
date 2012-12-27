<?php

namespace Bricks\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Bricks\SiteBundle\Entity\Brick;
use Bricks\MessageBundle\FormType\NewThreadMessageFromBrickFormType;

/**
 * Brick controller
 *
 * Integrate message functionalities into bricks
 */
class BrickController extends Controller
{
    /**
     * Show the UserMessage form to send a comment to the brick author, creating a new UserThread
     *
     * @Template()
     */
    public function _userMessageFormAction(Brick $brick)
    {
        $form = $this->createForm($this->container->get('message_bundle.bricks_message_new_thread_message_from_brick_form.type'), array(
            'brick' => $brick,
            'recipient' => $brick->getUser()
        ));
        
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Sends a message
     *
     * @Route("/ajax-send-from-brick", name="message_ajax_send_from_brick")
     * @Template()
     */
    public function ajaxSendFromBrickAction()
    {
        /*
         * set the sender
         * check if user is logged
         */
        $sender = $this->container->get('security.context')->getToken()->getUser();
        if (!$sender) {
            //$json = array('error' => 'It was impossible to set a sender');
            //return new Response(json_encode($json), 400, array('Content-Type'=>'application/json'));
            return new AccessDeniedException();
        }

        $form = $this->createForm($this->container->get('message_bundle.bricks_message_new_thread_message_from_brick_form.type'));

        // bind the request
        $form->bind($this->getRequest());

        // send a message
        if ($form->isValid()) {

            // retrieve form data
            $data = $form->getData();

            // UserMessage composer
            $composer = $this->container->get('fos_message.composer');

            // compose a new message, creating a new thread
            $message = $composer->newThread()
                ->setSender($sender)
                ->addRecipient($data['recipient'])
                ->setSubject("{$sender->getUsername()} comment")
                ->setBody($data['body'])
                ->getMessage()
            ;

            /*
             * set the Brick linked to the thread
             */
            $brick = $data['brick'];
            if ($brick) {
                $message->getThread()->setSubject("{$sender->getUsername()} comment about \"{$brick->getTitle()}\"");
                $message->getThread()->setBrick($brick);
            }

            // send the message
            $this->container->get('fos_message.sender')->send($message);

            return new Response(json_encode(array()), 200, array('Content-Type'=>'application/json'));
        }

        // initialize json empty array
        $json = array('errors' => array());

        // build json errors array
        $fields = $form->all();
        foreach ($fields as $field) {
            if ($field->hasErrors()) {
                $json['errors'][$field->getName()] = array();
                foreach ($field->getErrors() as $error) {
                    $json['errors'][$field->getName()][] = $error->getMessageTemplate();
                }
            }
        }

        return new Response(json_encode($json), 400, array('Content-Type'=>'application/json'));
    }
}
