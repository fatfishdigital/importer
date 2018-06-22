<?php
/**
 * importer plugin for Craft CMS 3.x
 *
 * content importer
 *
 * @link      Fatfish.com.au
 * @copyright Copyright (c) 2018 Fatfish
 */

namespace fatfish\importer\controllers;
use Craft;
use craft\web\Controller;
use fatfish\importer\services;
use fatfish\importer\services\EntrycategoriesService as Service;
use fatfish\importer\models\FeedModel;
use craft\web\Request;
use function Sodium\crypto_aead_aes256gcm_decrypt;
use TiBeN\CrontabManager\CrontabAdapter;
use TiBeN\CrontabManager\CrontabJob;
use TiBeN\CrontabManager\CrontabRepository;


/**
 * Contentimporter Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Fatfish
 * @package   Importer
 * @since     1.0.0
 */
class ContentimporterController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'render','save','RunCron','setcron','cronview'];
    /**
     * @var
     */
    public $apikey;
    public $apiurl;
    public $fieldlist;
    public $apifield;
    public $hour;
    public $minute;
    public $day;
    public $month;
    public $week;

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/importer/contentimporter
     *
     * @return mixed
     */


    public function actionIndex() //TODOS  we do not need this any longer we can remove
    {
        $result = 'Welcome to the ContentimporterController actionIndex() method';
        $obj=Craft::$app->plugins->getPlugin('importer')->getSettings();
        $this->apiurl = $obj->apiurl;
        $this->apikey = $obj->apikey;

        return $result;

    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/importer/contentimporter/do-something
     *
     * @return mixed
     */
    public function actionImportArticle() // TODOS we dont need this
    {

        $obj=Craft::$app->plugins->getPlugin('importer')->getSettings();
        $this->apiurl = $obj->apiurl;
        $this->apikey = $obj->apikey;
        $result = 'Welcome to the ContentimporterController actionDoSomething() method'.$this->apiurl;
//        $this->get_articles($this->apiurl,$this->apikey);
        return $result;
    }

//render control panel feedsettings
//pass arguments to template
// pass all the fields of choosen entries type to control panel


    public function actionRender()
    {

        $EntryId = (int)Craft::$app->plugins->getPlugin('importer')->getSettings()->entries;

        if($EntryId>0) {
            $sectionHandle = Craft::$app->sections->getSectionById($EntryId)->handle;
            $sectionId = Craft::$app->sections->getSectionByHandle($sectionHandle)->getEntryTypes();
            $this->fieldlist = Craft::$app->fields->getFieldsByLayoutId((int)$sectionId[0]->sectionId);
            $services = new services\EntrycategoriesService();
            $this->apifield = $services->fetch_api_field();
            return $this->renderTemplate('importer/controlpanel', ['fields' => $this->fieldlist, 'apifield' => $this->apifield]);

        }
        else
        {

            return $this->renderTemplate('importer/controlpanel',['fields'=>null,'apifield'=>null]);


        }

    }



    public function actionSave()
    {
/*
 * Saves Fields that are mapped with xml
 */

    $EntryModel = new \fatfish\importer\models\ImporterModel();
    $ImporterService = new services\EntrycategoriesService();
    $critearea_element='';
    $requestedparam = Craft::$app->request->post();
    $entryfield = $requestedparam['entryfield'];
    $mappedfield = $requestedparam['mappedfield'];

     if(isset($requestedparam['radio'])) {
         $critearea_element = $requestedparam['radio'];
     }


             for($i=0;$i<sizeof($mappedfield);$i++) {
              $EntryModel->entries_field = $entryfield[$i];
              $EntryModel->mapped_field = $mappedfield[$i];
              if(isset($critearea_element[$i]))
              {
                  if($critearea_element[$i]==='checked')
                  {
                      $EntryModel->critearea = 1;
                  }


              }
              else
              {
                  $EntryModel->critearea = 0;
              }

              $ImporterService->save_config($EntryModel);
          }

       Craft::info('Configuration Saved Successfully');
       Craft::$app->session->setNotice('Configuration Saved Successfully!!!');
       return $this->actionRender();
     }


     public function actionImport()
     {
         /*
          * This function imports xml data and saves into database with mapped field
          * First check whether field mapping is saved or not
          * if saved import data
          * if not
          * then
          * display message that field mapping is not saved.
          */



       $service = new Service();
       if($service->mapped_data_count()) {


           $service->fetch_xml(); // this will fetch and insert data into database.
           Craft::info('Data Imported Successfully');
           Craft::$app->session->setNotice('Data Imported Successfully!!!');

           return $this->actionRender();
       }
       else{

           Craft::error('Field Mapping is not Done');
           Craft::$app->session->setNotice('First Save the field mapping properly!!');

           return $this->actionRender();


       }

      }
      /* Checks whether is request is comming from console or not
       * if request is coming from console then insert data
       * you cannot insert data if you donot check request type
       * It will throw exception in Console
       *
       *
       */
      public function actionRunCron()
      {


         if(Craft::$app->request->getIsConsoleRequest())
         {
              $service = new Service();
             $service->fetch_xml(); // this will fetch and insert data into database.
             Craft::$app->log->logger->log("Data is imported at".\date("Y-m-d h:i:sa"),"0");
         }
         else {
             Craft::$app->log->logger->log("Data import failed at".\date("Y-m-d h:i:sa"),"0");
         }


      }
      public function actionCronview()
      {
         return  $this->renderTemplate('importer/cron');



      }

	/**
	 * cron job settings.
	 */
	public function actionSetcron()
      {


      	    $data = (object)Craft::$app->getRequest()->post();


			if (empty($data->hour) && empty($data->minute) && empty($data->day) && empty($data->month) && empty($data->week))
			{

					Craft::$app->session->setNotice("Empty Text Not allowed !!!");
			}
			else {

					$this->hour = isset($data->hour) ? $data->hour : '*';
					$this->minute = isset($data->minute) ? $data->minute : '*';
					$this->day = isset($data->day) ? $data->day : '*';
					$this->month = isset($data->month) ? $data->month : '*';
					$this->week = isset($data->week) ? $data->week : '*';

					try {
							$crontabRepository = new CrontabRepository(new CrontabAdapter());
							$crontabJob = new CrontabJob();
							$crontabJob->minutes = $this->minute;
							$crontabJob->hours = $this->hour;
							$crontabJob->dayOfMonth = $this->day;
							$crontabJob->months = $this->month;
							$crontabJob->dayOfWeek = $this - $this->week;
							$crontabJob->taskCommandLine = 'php ../craft importer/cronjob/index';
							$crontabJob->comments = 'plugin importer '; // Comments are persisted in the crontab
							$crontabRepository->addJob($crontabJob);
							$crontabRepository->persist();
							Craft::$app->session->setNotice("Cron job scheduled");
				} catch (\Exception $e) {
					craft::$app->session->setNotice($e->getMessage());
				}
			}
		return $this->renderTemplate('importer/cron');

	}

     public function actionFeeds()
     {
        $feedcategory = new FeedController(null,null); // dont know why this constructor expects two parameter ,if you see the base controller there is no constructor
        $feeds = $feedcategory->get_feed_type();
       return $this->renderTemplate('importer/feeds',['feeds'=>$feeds]);
     }



     public function actionFeedsettings()
     {

         return $this->renderTemplate('importer/feedsettings');
     }


     public function actionNewfeed()
     {
          $feedmodel = new FeedModel();
         $feedService = new services\FeedService();
         $AllFeedData = $feedService->get_all_feeds();
         return $this->renderTemplate('importer/addfeed',['feed'=>$feedmodel,'Feeds'=>$AllFeedData]);
     }



     public function actionEditfeed($feedId)
     {
         $feedmodel = new FeedModel();
         $feedService = new services\FeedService();
         $AllFeedData = $feedService->get_feeds($feedId);
         $feedmodel->id = $AllFeedData->id;
         $feedmodel->name = $AllFeedData->name;
         $feedmodel->feedurl = $AllFeedData->feedurl;

         return $this->renderTemplate('importer/addfeed',['feed'=>$feedmodel,'Feeds'=>$AllFeedData]);



     }
     public function actionDeletefeed($feedId)
     {
         $Feedservice = new services\FeedService();
         if($Feedservice->delete_feed($feedId))
         {
             $feedmodel = new FeedModel();
             $feedService = new services\FeedService();
             $AllFeedData = $feedService->get_all_feeds();
             Craft::$app->session->setNotice("Feed Deleted Sucessfully !!!");
             return $this->renderTemplate('importer/addfeed',['feed'=>$feedmodel,'Feeds'=>$AllFeedData]);

         }

     }
     public function actionFetchapi()
     {
			$feedController = new FeedController(null,null);
		if(Craft::$app->request->isAjax)
		{
			$ajax_post_data = Craft::$app->request->post('url');
			$data=$feedController->get_xml_node($ajax_post_data);
			return json_encode($data);
		}

     }
}
