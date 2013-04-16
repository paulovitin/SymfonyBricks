<?php
namespace Bricks\SiteBundle\Listener;

use Symfony\Component\Routing\RouterInterface;

use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapListener implements SitemapListenerInterface
{
    private $router;

    private $em;

    /**
     * array of _locale parameters used for the routes
     *
     * es: array(3) { [0]=> string(2) "en" [1]=> string(2) "it" [2]=> string(2) "es" }
     *
     * @var array
     */
    private $interfaceTranslationLocales;

    public function __construct(RouterInterface $router, $em, $interfaceTranslationLocales)
    {
        $this->router = $router;
        $this->em = $em;
        $this->interfaceTranslationLocales = array_keys($interfaceTranslationLocales);
    }

    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $section = $event->getSection();
        if (is_null($section) || $section == 'default') {

            // route: "index_not_localized"
            $event->getGenerator()->addUrl(
                new UrlConcrete($this->router->generate('index_not_localized', array(), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                'default'
            );

            // route: "login"
            $event->getGenerator()->addUrl(
                new UrlConcrete($this->router->generate('login', array(), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                'default'
            );

            // route: "logout"
            $event->getGenerator()->addUrl(
                new UrlConcrete($this->router->generate('logout', array(), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                'default'
            );


            foreach ($this->interfaceTranslationLocales as $_locale) {

                // route: "homepage"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('homepage', array('_locale' => $_locale), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "changelog"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('changelog', array('_locale' => $_locale), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "contribute"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('contribute', array('_locale' => $_locale), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "developers"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('developers', array('_locale' => $_locale), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "fos_user_security_login"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('fos_user_security_login', array(), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "fos_user_security_logout"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('fos_user_security_logout', array(), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "brick"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('brick', array('_locale' => $_locale), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "brick_show"
                foreach ($this->em->getRepository('BricksSiteBundle:Brick')->findBy(array('published' => true)) as $entity) {
                    $event->getGenerator()->addUrl(
                        new UrlConcrete($this->router->generate('brick_show', array('_locale' => $_locale, 'slug' => $entity->getSlug()), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                        'default'
                    );
                }

                foreach ($this->em->getRepository('BricksUserBundle:User')->findAll() as $entity) {
                    // route: "userprofile_show"
                    $event->getGenerator()->addUrl(
                        new UrlConcrete($this->router->generate('userprofile_show', array('_locale' => $_locale, 'username' => $entity->getUsername(),), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                        'default'
                    );
                    // route: "userprofile_bricks_publihed"
                    $event->getGenerator()->addUrl(
                        new UrlConcrete($this->router->generate('userprofile_bricks_publihed', array('_locale' => $_locale, 'username' => $entity->getUsername(),), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                        'default'
                    );
                }

                // route: "wiki_homepage"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('wiki_homepage', array('_locale' => $_locale), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "wiki_symfonybricks"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('wiki_symfonybricks', array('_locale' => $_locale), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );

                // route: "wiki_dictionary_brick"
                $event->getGenerator()->addUrl(
                    new UrlConcrete($this->router->generate('wiki_dictionary_brick', array('_locale' => $_locale), true), new \DateTime(), UrlConcrete::CHANGEFREQ_HOURLY, 1),
                    'default'
                );
            }

        }
    }
}