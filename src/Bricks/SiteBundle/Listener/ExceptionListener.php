<?php
namespace Bricks\SiteBundle\Listener;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    protected $templating;
    protected $kernel;

    public function __construct(EngineInterface $templating, $kernel)
    {
        $this->templating = $templating;
        $this->kernel = $kernel;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ('prod' == $this->kernel->getEnvironment()) {
            // exception object
            $exception = $event->getException();

            $message = $this->templating->render(
                'BricksSiteBundle:Exception:exception.html.twig',
                array('exception' => $exception)
            );

            // new Response object
            $response = new Response($message, $exception->getStatusCode());

            // set the new $response object to the $event
            $event->setResponse($response);
        }
    }
}
