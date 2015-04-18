<?php

namespace SkyResource\GetJobInGermanyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JobController extends Controller
{
    // show single job's page using provided job id
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
    
    
    // in case of an ajax call: return json document with job titles containing "keywordPart" string
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
    
    // in case of an ajax call: return json document with job cities containing "locationPart" string  
    /**
     * @Route("complete-location/{locationPart}", name="GetJobInGermanyBundle_completeLocation", defaults={"locationPart"=""})
     */
    public function completeLocationAction(Request $request, $locationPart) {
        
        if ($request->isXmlHttpRequest()) {
                
                $response = new JsonResponse();
                
                if ($locationPart!="") {
                
                  $em = $this->getDoctrine()->getManager();
                  $jobs = $em->getRepository('GetJobInGermanyBundle:Job')->getSimilarLocations($locationPart);          
                  $response->setData($jobs);
                  
                }
        
                return  $response;
        }
        
        else {
                throw new \Exception('Not Allowed');
        }
        
    }
    
}