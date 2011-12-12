<?php

namespace Jet\JustBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jet\JustBundle\Parser\Just;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="home")
     */
    public function indexAction()
    {
    	
		return $this->render("JetJustBundle:Mobile:Home.html.twig");

    }
    /**
     * @Route("/search",name="search")
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
    	
    	return $this->render('JetJustBundle:Mobile:Search.html.twig',Array('results' => $results));
    	
   	}
    /**
     * @Route("/restaurant/{restaurant}",name="restaurant")
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
    	return $this->render('JetJustBundle:Mobile:Restaurant.html.twig',Array('results' => $results));
    	
   	}
  

}
