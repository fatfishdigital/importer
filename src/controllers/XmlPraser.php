<?php
/**
 * Created by PhpStorm.
 * User: fatfish
 * Date: 19/4/18
 * Time: 3:01 PM
 */

namespace fatfish\importer\controllers;
use Craft;
use craft\feeds\GuzzleClient;
use GuzzleHttp\Client;
use Cake\Utility\Xml;

class XmlPraser
{

    public $feedurl;
    public $xml=[];
    public $response;
    public $PrimaryElement;
    public function __construct($FeedModel)
    {


        $this->feedurl = (string)$FeedModel->feedurl;
          $client = new Client();
        $request = $client->request('GET',$this->feedurl);
         $this->response  = $request->getBody();
        $this->PrimaryElement =(string) $FeedModel->primary_element;




    }
    public function parse_xml()
    {

    	$xml = new \SimpleXMLElement($this->response);
	    $proper_xml_form = $xml->asXML();
	    $xmlIterator = (array)new \SimpleXMLIterator($proper_xml_form);
	                foreach($xmlIterator[$this->PrimaryElement] as $key=>$value)
        	        {
                            array_push($this->xml,$key);
	                    }
	    return $this->xml;
    }
    public function get_primary_node_element()
    {







    }


}