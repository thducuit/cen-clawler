<?php

namespace App\Http\Sites;

use PHPHtmlParser\Dom;
use GuzzleHttp\Client;
/**
* 
*/
class Mogi
{
	private $_site = 'https://mogi.vn/';

	private $_dom;

	private $_client;

	private $_html;

	private $_response;

	public function __construct() {
		$this->_client = new \GuzzleHttp\Client;
		$this->_dom = new Dom;
		try {
			$this->_response = $this->_client->request('GET', 'https://mogi.vn');
			$this->_html = $this->_response->getBody();
        	dd($this->_html);
		}catch(Exception $e) {
            echo Psr7\str($e->getRequest());
		    if ($e->hasResponse()) {
		        echo Psr7\str($e->getResponse());
		    }
        }

		
	}

	public function get_anchor_home_page() {

    	$this->_dom->load($this->_html);
	}

	public function get_anchors() {

	}
}