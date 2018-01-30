<?php

namespace App\Http\Sites;

use PHPHtmlParser\Dom;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image as Image;
/**
* 
*/
class Mogi
{
	private $_site = 'https://mogi.vn';

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
			$statusCode = $this->_response->getStatusCode();
		}catch(Exception $e) {
			echo '[error]' . PHP_EOL;
            echo Psr7\str($e->getRequest());
		    if ($e->hasResponse()) {
		        echo Psr7\str($e->getResponse());
		    }
        }
	}

	public function get_anchor_home_page() {
    	$this->_dom->load($this->_html);
    	$anchors = $this->_dom->find('.footer-right-parent	a');
    	foreach($anchors as $anchor) {
    		$href = $anchor->getAttribute('href');
    		if( $this->_is_href($href) ) {
	    		DB::table('anchors')->insert(
				    [
				    	'link' => $this->_add_site_prefix($href), 
				    	'status' => 0,
				    	'type' => 'mogi'
				    ]
				);
			}
    	}
	}

	public function get_content() {
		$anchorObjects = DB::table('anchors')
				->select('id', 'link')
                ->where('status', 0)
                ->where('type', 'mogi')
                ->get();
        foreach($anchorObjects as $anchorObject) {

			if( !$this->_is_accept_site($anchorObject->link) ) {
				continue;
			}

			$anchor_id = $anchorObject->id;

			try{
				$this->_response = $this->_client->request('GET', $anchorObject->link);
				$this->_html = $this->_response->getBody();
				$statusCode = $this->_response->getStatusCode();
				

				if($statusCode == '200') {
					$this->_dom->load($this->_html);

					//get anchor
					$this->_get_anchors();

					//get content
					$this->_get_content($anchor_id);
				}
			}
			catch(Exception $e) {
				echo "[ERROR]: " . $e->getMessage();
				//
				DB::table('anchors')
	            ->where('id', $anchor_id)
	            ->update(['status' => 1]);
			}
        }
	}

	private function _get_anchors() {
		$anchors = $this->_dom->find('a');
		foreach ($anchors as $anchor) {
			$href = $this->_add_site_prefix( $anchor->getAttribute('href') );
			$existedAnchor = DB::table('anchors')->where('link', $href)->first();
			if(!$existedAnchor) {
				if( $this->_is_href($href) ) { 
					DB::table('anchors')->insert(
					    [
					    	'link' => $href, 
					    	'status' => 0,
					    	'type' => 'mogi'
					    ]
					);
				}
			}
		}
	}

	private function _get_content($anchor_id) {
		$title = $this->_dom->find('#property #property-intro .title');
		$price = $this->_dom->find('#property #property-intro .price');
		$address = $this->_dom->find('#property #property-intro .address');
		$content = $this->_dom->find('#property #property-detail .property-info-content');
		$photos = $this->_dom->find('#property #gallery #top-media .media-item img');

		$contact = $this->_dom->find('.broker-contact-left');

		$photo_array = [];
		foreach ($photos as $photo) {
			$path = $photo->getAttribute('src');
			$filename = basename($path);
			Image::make($path)->save(public_path('images/mogi/' . $filename));
			$photo_array[] = $filename;
		}
		
		if( $title->count() > 0 ) {

			try{
				DB::table('contents')->insert(
				    [
				    	'title' => $title->innerHtml, 
				    	'price' => !empty($price) ? $price->innerHtml:'',
				    	'address' => !empty($address) ? $address->innerHtml:'',
				    	'photo' => json_encode($photo_array),
				    	'info' => isset($content[0]) ? htmlentities($content[0]->innerHtml):'',
				    	'detail' => isset($content[1]) ? htmlentities($content[1]->innerHtml):'',
				    	'contact' => isset($contact[0]) ? htmlentities($contact[0]->innerHtml):'',
				    	'anchor_id' => $anchor_id
				    ]
				);

				DB::table('anchors')
	            ->where('id', $anchor_id)
	            ->update(['status' => 2]);
			}
			catch(Exception $e) {
				echo "[ERROR]: " . $e->getMessage();
				//
				DB::table('anchors')
	            ->where('id', $anchor_id)
	            ->update(['status' => 1]);
			}
		}
	}

	private function _add_site_prefix($href = '') {
		if(substr($href, 0, 1) == '/') {
    		return $href = $this->_site . $href;
    	} 
		return $href;
	}

	private function _is_href($href = '') {
		$patterns = [
			'/^mailto:/',
			'/^tel:/',
			'/^sms:/',
			'/^#/'
		];

		if($href == '#' || $href == 'javascript:void(0);' || $href == 'javascript;') {
    		return false;
    	}

    	if(empty($href)) {
    		return false;
    	}

    	for($i = 0; $i<count($patterns); $i++) {
    		if( preg_match($patterns[$i], $href, $matches) ) {
    			return false;
    		}
    	}

    	return true;
	}

	private function _is_accept_site($href = '') {
		$patterns = [
			'/^(https\:\/\/mogi\.vn)/'
		];

		for($i = 0; $i<count($patterns); $i++) {
    		if( preg_match($patterns[$i], $href, $matches) ) {
    			return true;
    		}
    	}
    	return false;
	}

}