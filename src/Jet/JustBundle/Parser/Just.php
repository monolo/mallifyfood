<?php
namespace Jet\JustBundle\Parser;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;


/**
 *
 * @package IgaNewsBundle
 * @author  Iván Guillén <zeoipx@gmail.com>
 */
class Just extends BaseCrawler {


	private $url;

	private $baseurl;

	public function __construct($url,$baseurl){
		$this->url = $url;
		$this->baseurl = $baseurl;
	}

	public function setBaseUrl($url){
		$this->baseurl = $url;
	}

	public function setUrl($url){
		$this->url = $url;
	}
	

private function get_inner_html( $node ) { 
    $innerHTML= ''; 
    $children = $node->childNodes; 
    foreach ($children as $child) { 
        $innerHTML .= $child->ownerDocument->saveXML( $child ); 
    } 

    return $innerHTML; 
} 


	/*
	* Método obtener un restaurante determinado
	* Devuelve un Array:
	* 0 =>
	*      [id] => 1
	*      [slug] => /restaurants-slug/menu
	*      [slug] => /restaurants-slug/menu
	*
	*/
	function restaurant($slug){

		$client = new Client();



		$crawler = $client->request('GET',$this->url . '/restaurants-' . $slug . '/menu');


		$serverparams = $client->getHistory()->current()->getServer();

    	$baseurl = (empty($serverparams['HTTP_HOST'])) ? $this->baseurl : 'http://' . $serverparams['HTTP_HOST'];
    	$titles = $crawler->filter('.H1MC')->filter('span')->extract(Array('_text'));
    	
    	
    	
    	$locations = $crawler->filter('#ctl00_ContentPlaceHolder1_RestInfo_lblRestAddress')->extract(Array('_text'));
    	$zips = $crawler->filter('#ctl00_ContentPlaceHolder1_RestInfo_lblRestZip')->extract(Array('_text'));
    	
    	$image = $crawler->filter('#ctl00_ContentPlaceHolder1_RestInfo_imageTable')->attr('style');
    	
    	$image = str_replace('height:100px;width:100px;background-image:url(','',$image);
    	$image = str_replace(');','',$image);
    	
    	$restaurant = Array();
    	$restaurant['slug'] = $slug;
    	$restaurant['title'] = $titles[0];
    	$restaurant['location'] = $locations[0] . " , " . $zips[0];
    	$restaurant['image'] = $image;
    	
    	
    	
    	
		$restaurant['categories'] = $crawler->filter('.H2MC')
		->each(function($node,$i){
			
			$crawler = new Crawler($node->parentNode->parentNode->parentNode->nextSibling->nextSibling);
			$crawler_categories = new Crawler($node);
			
			$category = Array();
			$titles = $crawler_categories->extract(Array('_text'));
			
			
			$category['title'] = utf8_decode($titles[0]);
			$category['products'] = $crawler->filter('.prdDe')->each(function($node,$i){
			
			
			$product = Array();
			
			$crawler = new Crawler($node->parentNode);
			
			$titles = $crawler->filter('.prdDe h6')->extract(Array('_text'));
			$numbers = $crawler->filter('.prdNo h6')->extract(Array('_text'));
			$prices = $crawler->filter('.prdPr div')->eq(1)->extract(Array('_text'));
			
			
			
			$product['number'] = $numbers[0]; 
			$product['title'] = utf8_decode($titles[0]); 
			$product['price'] = $prices[0]; 
			
			return $product;
			
			});
			
			return $category;
			
			
		});
		

		return $restaurant;

	}
	/*
	* Método obtener restaurantes de un código postal de JustEat
	* Devuelve un Array:
	* 0 =>
	*      [id] => 1
	*      [slug] => /restaurants-slug/menu
	*
	*/
	function search($postal){

		$client = new Client();



		$crawler = $client->request('GET',$this->url . '/area/' . $postal);


		$serverparams = $client->getHistory()->current()->getServer();

    	$baseurl = (empty($serverparams['HTTP_HOST'])) ? $this->baseurl : 'http://' . $serverparams['HTTP_HOST'];
    	
		$response = $crawler->filter('#SearchResults')->filter('article')
		->each(function($node,$i){
			
			$restaurant = Array();
			$restaurant['id'] = $node->getAttribute('data-restaurantid');
			$crawler = new Crawler($node);
			$data = $crawler->filter('h3 a')->extract(Array('href','_text'));
			$locations = $crawler->filter('address')->extract(Array('_text'));
			$types = $crawler->filter('section.menuDetails strong')->extract(Array('_text'));
			
			$dts = $data[0][0];
			$dts = str_replace('/restaurants-','',$dts);
			$dts = str_replace('/menu','',$dts);
			
			
			$restaurant['path'] = $dts;
			$restaurant['title'] = utf8_decode($data[0][1]);
			$restaurant['location'] = utf8_decode($locations[0]);
			$restaurant['type'] = utf8_decode($types[0]);
		
			$restaurant['open'] = false;
			
			$images = $crawler->filter('a.restaurantLogo img')->extract(Array('src'));
			if(count($images) > 0){
			
			$restaurant['open'] = true;
			
			$restaurant['image'] = $images[0];
			}


			return $restaurant;
		
		});
		

		return $response;

	}

}
?>