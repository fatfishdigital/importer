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


         $this->xml= array_keys((array)new \SimpleXMLElement($this->response));
        return $this->xml;

    }


}