<?php

namespace SkyResource\GetJobInGermanyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JobController extends Controller
{
    

/*    

    public function searchByCityAction($city, $page=1) {
        
        $limit = 30;
        
        $em = $this->getDoctrine()->getManager();
        
        $jobs = $em->getRepository('GetJobInGermanyBundle:Job')
                    ->getJobsByCity($city, $page, $limit);
        $totalNumberOfJobs = $jobs->count();
        $maxPages = ceil($totalNumberOfJobs / $limit);
        $thisPage = $page;
        
            return $this->render('GetJobInGermanyBundle:Job:resultsCity.html.twig', array('city' => $city, 'jobs' => $jobs,                   'maxPages'=> $maxPages,                    'thisPage' => $thisPage, 'totalNumberOfJobs' => $totalNumberOfJobs));
    }
    
*/  
    
    /**
     * @Route("job/{id}-{slug}", name="GetJobInGermanyBundle_show")
     */    
    public function showJobAction($id) {
        
        $em = $this->getDoctrine()->getManager();

        $job = $em->getRepository('GetJobInGermanyBundle:Job')->find($id);
        
        $response = $this->render('GetJobInGermanyBundle:Job:details.html.twig', array(
                        'job'      => $job));
        
        $response->setSharedMaxAge(21600);
        
        $response->setPublic();

        if (!$job) {
            throw $this->createNotFoundException('Unable to find desired job.');
        }
            return $response;
    }
    
    
    /**
     * @Route("complete-job/{keywordPart}", name="GetJobInGermanyBundle_completeJob", defaults={"keywordPart"=""})
     */
    public function completeJobAction(Request $request, $keywordPart) {
        
        if ($request->isXmlHttpRequest()) {
        
                $response = new JsonResponse();
                
                if ($keywordPart!="") {
                
                  $em = $this->getDoctrine()->getManager();
                  $jobs = $em->getRepository('GetJobInGermanyBundle:Job')->getSimilarJobs($keywordPart);
                  $response->setData($jobs);
                
                }
        
                return $response;
        }
        
        else {
                throw new \Exception('Not Allowed');
        }
        
    }
    
    /**
     * @Route("complete-location/{locationPart}", name="GetJobInGermanyBundle_completeLocation", defaults={"locationPart"=""})
     */
    public function completeLocationAction(Request $request, $locationPart) {
        
        if ($request->isXmlHttpRequest()) {
                
                $response = new JsonResponse();
                
                if ($locationPart!="") {
                
                  $em = $this->getDoctrine()->getManager();
                  $jobs = $em->getRepository('GetJobInGermanyBundle:Job')->getSimilarLocations($locationPart);
                  
        /*          $locations = array();
                  
                  foreach ($jobs as $job) {
                        $locations[] = $job['city'];
                  }
        */          
                  $response->setData($jobs);
                
                }
        
                return  $response;
        }
        
        else {
                throw new \Exception('Not Allowed');
        }
        
    }
    
/*    
    private function getPaginationData($totalNumberOfJobs, $currentPage, $limit) {
        $paginationData = array();
        $paginationData['maxPages'] = ceil($totalNumberOfJobs / $limit);
        if ($paginationData)['maxPages'] <10 {
            return true;
        }
        
        if ($currentPage==1) {
            $paginationData['startPage'] = 1;
            $paginationData['endPage'] = max
        }
        $paginationData['startPage'] = ();
    }

*/
    
}