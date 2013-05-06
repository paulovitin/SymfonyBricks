<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Finder\Finder;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BricksSiteBundle:Brick')->findPublished();

        return array(
            'bricks' => $entities
        );
    }
    
    /**
     * @Route("/changelog", name="changelog")
     * @Template()
     */
    public function changelogAction()
    {
        $finder = new Finder();
        $finder->files()
            ->in($this->container->getParameter('kernel.root_dir').'/../')
            ->depth('== 0')
            ->name('CHANGELOG.md')
        ;
        
        foreach ($finder as $file) {
            $changelog_content = file_get_contents($file->getRealPath());
        }
        
        return array(
            'changelog_content' => $changelog_content
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
    
    /**
     * @Route("/developers", name="developers")
     * @Template()
     */
    public function developersAction()
    {
        return array();
    }
}
