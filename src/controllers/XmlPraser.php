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
    public function __construct($FeedModel)
    {

        $this->feedurl = $FeedModel->feedurl;
        $client = new Client();
        $request = $client->request('GET',$this->feedurl);
         $this->response  = $request->getBody();


    }
    public function parse_xml()
    {


    	$xml = new \SimpleXMLElement($this->response);
	    $proper_xml_form = $xml->asXML();
	    $xmlIterator = new \SimpleXMLIterator($proper_xml_form);
	       foreach($xmlIterator->children() as $child)
	     {

			if($child->children())
			{

				foreach ($child->children() as $secondchild)
				{

					return (array_keys((array)$secondchild));
					break;
				}

			}
			else
			{


				return array_keys((array)$child);
			}

	    }


    }
    public function get_primary_node_element()
    {







    }


}