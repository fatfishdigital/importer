<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22/3/18
 * Time: 12:19 PM
 */

namespace fatfish\importer\services;
use Craft;
use fatfish\importer\models\ImporterModel;
use fatfish\importer\records\FeedMappingRecord;
use fatfish\importer\records\FeedRecord;
use fatfish\importer\records\ImporterRecord;
use GuzzleHttp\Client;
use function GuzzleHttp\Promise\all;
use yii\base\Component;
use fatfish\importer\records\ImporterRecord as Record;
use yii\db\Exception;
use \Sabre\Xml\Service;
use \craft\elements\Entry;

class EntrycategoriesService extends Component
{
    public $apiurl;
    public $apikey;
    public $response;
    public $connection;
    public $command;
    public $endpoint;
    public $responsebody;
    public $fields=[];
    public $allarticles=[];
    public $flag;

	/**
	 * EntrycategoriesService constructor.
	 * @param array $config
	 */
	public function __construct(array $config = [])
            {
              parent::__construct($config);
            }


        public function fetch_api_field()
        {
        	if(is_null($this->responsebody))
	        {
	        	return null;
	        }
            $newsurl = (string)$this->responsebody->newsListItem['href'];
            $field_array = [];
            try {
                $client = new Client();
                $request = $client->request('GET', $newsurl);
                $response = $request->getBody();


                $responseBody = (array)new \SimpleXMLElement($response);
                $field_array = array_keys($responseBody);
                return $field_array;
            }
            catch (\Exception $exception)
            {
                Craft::info('Something Went Wrong'.$exception->getMessage());
            }
        }

    /**
     * @param $importerModel
     * @throws Exception
     * will save the configuration to database.
     */
    public function save_config($importerModel)
        {
           $importerRecord = new \fatfish\importer\records\ImporterRecord();
            $importerRecord->entries_field=$importerModel->entries_field;
            $importerRecord->mapped_field = $importerModel->mapped_field;
            $importerRecord->critearea = $importerModel->critearea;
                        $transaction = Craft::$app->getDb()->beginTransaction();
            try
            {
                $importerRecord->save(true);
                $transaction->commit();
            }
            catch (Exception $e)
            {
                $transaction->rollBack();
                throw $e;
            }
        return true;


        }

         public function parse_xml($xml,$SavedFeed)
         {



             $this->get_fields($SavedFeed->id);
             $xmlarray = (array)$xml;
             $xmlarray=$xmlarray[$SavedFeed->primary_element];
                  $fields = [];
             $handleId = (int)$SavedFeed->entry_type;
             $entrytype = new \craft\records\EntryType();
             $entryType = $entrytype->find()->where(['id' => $handleId])->one();
             $entry = new \craft\elements\Entry();
             $entry->sectionId = $entryType->getAttribute('sectionId');
             $entry->typeId = $entryType->getAttribute('id');

             foreach ($this->fields as $key => $value) {
                 if (array_key_exists($value, $xmlarray)) {
                     $customfield = strtolower($key);
                     $fields[strtolower($key)] = $xmlarray->$value;
                     $entry->$customfield = $xmlarray->$value;
                     $entry->title = (string)$xmlarray->$value;
                     $entry->authorId = Craft::$app->user->id;

             }
             }

                $entry->fields($fields);

               if (Craft::$app->elements->saveElement($entry)) {
                      return true;

                     } else {
                     throw new \Exception("Couldn't Import Xml Data: " . print_r($entry->getErrors(), true));
                    }


        }


        public function fetch_xml($FeedId)
        {

            $GetSavedFeed = FeedRecord::find()->where(['id'=>$FeedId])->one();

            $Xmlobject= new Service();
            $client = new Client();



            $request = $client->request('GET', $GetSavedFeed->feedurl);

            $response = new \SimpleXMLElement($request->getBody());
            $this->parse_xml($response,$GetSavedFeed);

            return true;
        }

        public function get_fields($id)
        {

            $ImporterModel = FeedMappingRecord::find()->select('entries_field,mapped_field,critearea')->where(['importer_feeds_id'=>$id])->all();

            foreach($ImporterModel as $data)
            {
               $field_handle = Craft::$app->fields->getFieldById($data->entries_field);
               $this->fields["$field_handle"]=$data->mapped_field;

            }

        }
        public function get_critearea_content_check($response_data)
        {
              $Entrycontent=[];
              $sectionid =(int) \fatfish\importer\Importer::$plugin->getSettings()->entries;
              $SectionHandle = Craft::$app->sections->getSectionById($sectionid);
              $ImporterCriteareaModel = Record::find()->select('entries_field,mapped_field')->where(['critearea'=>1])->all();
              $fieldhandle = Craft::$app->fields->getFieldById($ImporterCriteareaModel[0]->entries_field)->handle;
              $mapped_field=$ImporterCriteareaModel[0]->mapped_field;
              $allEntry = Entry::find()->section($SectionHandle)->all();
                 foreach($allEntry as $entry) {
                  $Entrycontent []= $entry->getBehavior('customFields')->$fieldhandle; // $ent

              }
               if (!in_array($response_data->$mapped_field,$Entrycontent)) {
                     return true;
                  }
        }
        public function mapped_data_count($id)
        {
            /*
             * return boolean
             * if field mapping is done returns true
             * if field mapping is not done returns false
             */

            $RecordCount = FeedMappingRecord::find()->where(['importer_feeds_id'=>$id])->count('*');
            if($RecordCount<=0)
            {
                return false;
            }
                return true;


        }
}