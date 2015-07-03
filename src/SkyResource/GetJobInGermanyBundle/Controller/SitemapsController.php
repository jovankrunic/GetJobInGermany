<?php
// src/SkyResource/GetJobInGermanyBundle/Controller/SitemapsController.php

namespace SkyResource\GetJobInGermanyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SitemapsController extends Controller
{
    // generate sitemap by generating links for all the website pages
    /**
     * @Route("/sitemap40934gbvcf3w5tthf.{_format}", name="sample_sitemaps_sitemap", Requirements={"_format" = "xml"})
     * @Template("GetJobInGermanyBundle:Sitemaps:sitemap.xml.twig")
     */
    public function sitemapAction() 
    {
        $em = $this->getDoctrine()->getManager();
        
        $urls = array();
        $hostname = $this->getRequest()->getHost();

        // add homepage url
        $urls[] = array('loc' => $this->get('router')->generate('GetJobInGermanyBundle_home'), 'changefreq' => 'daily', 'priority' => '0.7');


        $urls[] = array('loc' => $this->get('router')->generate('GetJobInGermanyBundle_contact'), 'changefreq' => 'monthly', 'priority' => '0.2');
        
      // add categories urls
      foreach ($em->getRepository('GetJobInGermanyBundle:Category')->findAll() as $category) {
        if ($category->getSlug()=="") {
            continue;
        }
        else {
            $urls[] = array('loc' => $this->get('router')->generate('GetJobInGermanyBundle_search', 
                    array('category' => $category->getSlug())), 'changefreq' => 'daily', 'priority' => '0.8');
        }
        }
        
        
      $cities = array("Berlin","Hamburg","München","Köln","Frankfurt","Stuttgart","Düsseldorf","Dortmund","Essen","Bremen","Dresden","Leipzig","Hannover","Nürnberg","Duisburg","Bochum","Wuppertal","Bonn","Bielefeld","Mannheim","Karlsruhe","Münster","Wiesbaden","Augsburg");
       
        // add cities urls
        foreach ($cities as $city) {
            $urls[] = array('loc' => $this->get('router')->generate('GetJobInGermanyBundle_searchCity', 
                    array('city' => $city)), 'changefreq' => 'daily', 'priority' => '0.9');
        }
        
        // add single jobs urls
        foreach ($em->getRepository('GetJobInGermanyBundle:Job')->getLatestJobs(50) as $job) {
            $urls[] = array('loc' => $this->get('router')->generate('GetJobInGermanyBundle_show', 
                    array('id' => $job->getId(), 'slug' => $job->getSlug())), 'priority' => '1');
        }

/*        
        // urls from database
        $urls[] = array('loc' => $this->get('router')->generate('home_product_overview', array('_locale' => 'en')), 'changefreq' => 'weekly', 'priority' => '0.7');
        // service
        foreach ($em->getRepository('AcmeSampleStoreBundle:Product')->findAll() as $product) {
            $urls[] = array('loc' => $this->get('router')->generate('home_product_detail', 
                    array('productSlug' => $product->getSlug())), 'priority' => '0.5');
        }
*/

        $response = $this->render('GetJobInGermanyBundle:Sitemaps:sitemap.xml.twig', array('urls' => $urls, 'hostname' => $hostname));
        
        $response->setSharedMaxAge(345600);
        $response->setPublic();
        
        return $response;
        
    }
}