<?php
/**
 * Created by PhpStorm.
 * User: fatfish
 * Date: 13/4/18
 * Time: 3:58 PM
 */

namespace fatfish\importer\services;
use fatfish\importer\models\FeedModel;
use fatfish\importer\records\FeedRecord;
use Craft;
use craft\base\Component;
use  GuzzleHttp\Client;

class FeedService extends Component
{

    public function save_feed($FeedModel)
    {

        $FeedRecord = new \fatfish\importer\records\FeedRecord();
        $FeedRecord->name = $FeedModel->name;
        $FeedRecord->feedurl = $FeedModel->feedurl;
        $FeedRecord->feedtype = $FeedModel->feedtype;
        $FeedRecord->entry_type = $FeedModel->Entrytype;
        $FeedRecord->primary_element = $FeedModel->primary_element;

        $transaction = Craft::$app->db->beginTransaction();
        try
        {
            $FeedRecord->save(true);
            $transaction->commit();
        }
        catch (Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
        return true;


    }



    public function update_feed($FeedModel)
    {



        $FeedRecord = new \fatfish\importer\records\FeedRecord();
        $FeedRecord->id = $FeedModel->id;
        $FeedRecord->name = $FeedModel->name;
        $FeedRecord->feedurl = $FeedModel->feedurl;
        $FeedRecord->feedtype = $FeedModel->feedtype;
        $FeedRecord->entry_type = $FeedModel->Entrytype;
        $FeedRecord->primary_element = $FeedModel->primary_element;
        $FeedModel->dateUpdated = date('Y-m-d h:i:s');
        $transaction = Craft::$app->db->beginTransaction();
        try
        {
            $FeedRecord::updateAll($FeedRecord);
            $transaction->commit();

        }
        catch (Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }

        return true;



    }







    public function get_all_feeds()
    {
        $FeedRecord = new FeedRecord();
        $FeedModel = new FeedModel();
        $FeedModel = $FeedRecord::find()->all();

        return $FeedModel;
    }
    public function get_feeds($feedId)
    {
        $FeedRecord = new FeedRecord();
        $FeedModel = new FeedModel();
        $FeedModel = $FeedRecord::find()->where(['id'=>$feedId])->one();

        return $FeedModel;






    }
    public function delete_feed($feedId)
    {

        $FeedRecord = new FeedRecord();
        if($FeedRecord::deleteAll(['id'=>$feedId]))
        {
            return true;
        }
        return false;

    }

    public function feed_type()
    {

        $FeedRecord = new FeedRecord();
        $FeedRecordtype = $FeedRecord::find()->all();
        return $FeedRecordtype;

    }


    public function get_entries_field()
    {




    }
    public function xml_api_element_fetched($url)
    {

	    $client = new Client();
	    $request = $client->request('GET',$url);
	    $response  = $request->getBody();
	    $xml = new \SimpleXMLElement($response);
	    $proper_xml_form = $xml->asXML();
	    $xmlIterator = new \SimpleXMLIterator($proper_xml_form);
	    return (array_keys((array)$xmlIterator));

    }

}