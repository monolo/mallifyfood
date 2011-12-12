<?php

namespace Jet\JustBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jet\JustBundle\Parser\Just;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
* @Route("/api",name="api")
*/
class ApiController extends Controller
{
    /**
     * @Route("/search",name="api_search")
     */
    public function searchAction()
    {
    $request = $this->getRequest();
    $code = $request->query->get('code');


    	$just = new Just("http://www.just-eat.es","http://www.just-eat.es");
    	
    	
    	
    	$results = $just->search($code);
    	
    	//have many restaurants, have postal.
    	if(count($results) > 1){
    		//create or update Postal
    		
    		//create or update each Result
    		
    	}
    	
    	return new Response(json_encode($results));
    	
   	}
    /**
     * @Route("/restaurant/{restaurant}",name="api_restaurant")
     */
    public function restaurantAction($restaurant)
    {
    


    	$just = new Just("http://www.just-eat.es","http://www.just-eat.es");
    	
    	
    	
    	$results = $just->restaurant($restaurant);
    	
    	//have many restaurants, have postal.
    	if(count($results) > 1){
    		//create or update Postal
    		
    		//create or update each Result
    		
    	}
    	return new Response(json_encode($results));
    	
   	}
  

}
