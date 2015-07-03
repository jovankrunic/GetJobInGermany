<?php

namespace SkyResource\GetJobInGermanyBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use SkyResource\GetJobInGermanyBundle\Entity\Enquiry;
use SkyResource\GetJobInGermanyBundle\Form\EnquiryType;

use SkyResource\GetJobInGermanyBundle\Entity\Search;
use SkyResource\GetJobInGermanyBundle\Form\SearchType;

class PageController extends Controller
{
    
    // show index page containing numbers of jobs in database (also per category/city)
    /**
     * @Route("/", name="GetJobInGermanyBundle_home")
     */
    public function indexAction() {
        
        $search = new Search();
        $form = $this->createForm(new SearchType(), $search, array('csrf_protection' => false));
        
        $em = $this->getDoctrine()
               ->getManager();
        
        $numberOfJobs = array();
        
        $numberOfJobs['all'] = $em->getRepository('GetJobInGermanyBundle:Job')
                         ->getNumberOfAllJobs();
        
        $numberOfJobsByCategory = $em->getRepository('GetJobInGermanyBundle:Job')
                ->getNumberOfJobsByCategory();
                
        $numberOfJobs = array_merge($numberOfJobs, $em->getRepository('GetJobInGermanyBundle:Job')
                ->getNumberOfJobsByCategory());
        
        $response = $this->render('GetJobInGermanyBundle:Page:index.html.twig', array('form' => $form->createView(),'numberOfJobs'=>$numberOfJobs));
        
        $response->setSharedMaxAge(1800);
        $response->setPublic();
        return $response;
    }
    
    // show sidebar
    public function sidebarAction() {
        
        $em = $this->getDoctrine()
               ->getManager();

        $jobLimit = $this->container
                           ->getParameter('getjobingermany.jobs.latest_job_limit');
    
        $latestJobs = $em->getRepository('GetJobInGermanyBundle:Job')
                         ->getLatestJobs($jobLimit);
                         
        $response = $this->render('GetJobInGermanyBundle:Page:sidebar.html.twig', array(
            'latestJobs'    => $latestJobs
        ));
                                  
        $response->setSharedMaxAge(600);
        $response->setPublic();

        return $response;
    }

    // show search result page with jobs from selected city using city link from the index page
    /**
     * @Route("jobs-in-{city}/{page}", name="GetJobInGermanyBundle_searchCity", defaults={"page"=1})
     */    
    public function searchByCity($city, $page) {
        
        $search = new Search();
        $form = $this->createForm(new SearchType(), $search, array('csrf_protection' => false));
        
        $limit = $this->container
                    ->getParameter('getjobingermany.jobs.jobs_per_page');
        
        $request = $this->getRequest();
        $form->get('location')->setData($city);
        
        $page = (($page!="" && (string)(int)$page==$page && $page>0)?$page:1);
        
        $em = $this->getDoctrine()->getManager();
        
        $jobs = $em->getRepository('GetJobInGermanyBundle:Job')
                    ->getJobsByCity($city, $page);
        
        $totalNumberOfJobs = count($jobs);
        
        $paginationNumbers = $this->getPaginationNumbers($totalNumberOfJobs, $limit, $page);
        
        $response = $this->render('GetJobInGermanyBundle:Page:listResults.html.twig', array('city'=>$city, 'jobs' =>$jobs, 'minPage' =>                     $paginationNumbers['minPage'], 'maxPage'=>$paginationNumbers['maxPage'], 'maxPages'=> $paginationNumbers['maxPages'], 'thisPage' => $page, 'totalNumberOfJobs' => $totalNumberOfJobs, 'form' => $form->createView()));
        
        $response->setETag(md5($response->getContent()));
        $response->setPublic(); // make sure the response is public/cacheable
        $response->isNotModified($request);
        
        return $response;
    }

    // show contact page
    /**
     * @Route("/contact", name="GetJobInGermanyBundle_contact")
     */    
    public function contactAction() {
      $enquiry = new Enquiry();
      $form = $this->createForm(new EnquiryType(), $enquiry);

      $request = $this->getRequest();
      if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $message = \Swift_Message::newInstance()
            ->setFrom('contact@getjobingermany.skyresource.com', 'GetJobInGermany')
            ->setTo('jovan.krunic@gmail.com')
            ->setSubject('New contact message')
            ->setReplyTo($enquiry->getEmail())
            ->setBody($this->renderView('GetJobInGermanyBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
          $this->get('mailer')->send($message);

          //  $this->get('session')->setFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');
          $this->get('session')->getFlashBag()->add('contact-notice', 'Your message was successfully sent to us. Thank you for your feedback!'                  );
            return $this->redirect($this->generateUrl('GetJobInGermanyBundle_contact'));
        }
      }

      return $this->render('GetJobInGermanyBundle:Page:contact.html.twig', array(
        'form' => $form->createView()
      ));
    }
    
    // show search result page for jobs in all or in particular category
    /**
     * @Route("jobs/{category}", name="GetJobInGermanyBundle_search", defaults={"category"=""})
     */    
    public function searchAction($category) {
        
        $search = new Search();
        $form = $this->createForm(new SearchType(), $search, array('csrf_protection' => false));
        $limit = $this->container
                    ->getParameter('getjobingermany.jobs.jobs_per_page');
        
        $request = $this->getRequest();
        $data = $request->query->all();
        $children = $form->all();
        $data = array_intersect_key($data, $children);
        $form->bind($data);
        
        $keyword = $search->getKeyword();
        $location = $search->getLocation();
        $useTimeLimit = $search->getUseTimeLimit();
        $timeLimitVal = $search->gettimeLimitVal();
        
    //  $category = $request->query->get('category');
        $page = $request->query->get('page');
        
        $page = (($page!="" && (string)(int)$page==$page && $page>0)?$page:1);
        
        $em = $this->getDoctrine()->getManager();
        
        $jobs = $em->getRepository('GetJobInGermanyBundle:Job')
                    ->getJobsBySearchCriteria($keyword, $location, $category, $useTimeLimit, $timeLimitVal, $page);
                    
        $totalNumberOfJobs = count($jobs);
         
        $categoryName = "";
        
        if ($category!="" && $totalNumberOfJobs>0) {
            $categoryName = $em->getRepository('GetJobInGermanyBundle:Category')
                    ->getCategoryName($category);
        }
        
        $paginationNumbers = $this->getPaginationNumbers($totalNumberOfJobs, $limit, $page);
        
        $response = $this->render('GetJobInGermanyBundle:Page:searchResults.html.twig', array('minPage' => $paginationNumbers['minPage'], 'maxPage'=>$paginationNumbers['maxPage'], 'jobs' => $jobs, 'category' => $category, 'categoryName'=>$categoryName, 'maxPages'=> $paginationNumbers['maxPages'], 'thisPage' => $page, 'totalNumberOfJobs' => $totalNumberOfJobs, 'form' => $form->createView()));
        
        $response->setETag(md5($response->getContent()));
        $response->setPublic(); // make sure the response is public/cacheable
        $response->isNotModified($request);
        
        return $response;
    
    }
    
    // helper function used only in this controller, which calculates numbers in order to correctly show pagination area
    private function getPaginationNumbers($numberOfJobs, $limit, $page) {
        
        $paginationNumbers = array();
        $paginationNumbers['maxPages'] = ceil($numberOfJobs / $limit);
        $paginationNumbers['minPage'] = 2;
        $paginationNumbers['maxPage'] = $paginationNumbers['maxPages']-1;
        if ($paginationNumbers['maxPages']>7) {
            $paginationNumbers['minPage'] = (($page-1<2)?2:$page-1);
            $diffPages = 2 - ($page-$paginationNumbers['minPage']);
            $paginationNumbers['maxPage'] = (($page+$diffPages>$paginationNumbers['maxPage'])?$paginationNumbers['maxPage']:$page+$diffPages);
        }
        return $paginationNumbers;
        
    }
    
}