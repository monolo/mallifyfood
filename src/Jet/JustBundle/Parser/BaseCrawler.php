<?php
namespace Jet\JustBundle\Parser;

class BaseCrawler {

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


}
?>