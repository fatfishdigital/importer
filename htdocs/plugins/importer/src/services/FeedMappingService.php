<?php
	/**
	 * Created by PhpStorm.
	 * User: fatfish
	 * Date: 20/4/18
	 * Time: 11:41 AM
	 */

	namespace fatfish\importer\services;
	use fatfish\importer\models\FeedMappingModel;
	use fatfish\importer\records\FeedMappingRecord;
	use Craft;
	use craft\base\Component;

	class FeedMappingService extends Component
	{

		public function save_feed_mapping($model)
		{
			var_dump($model);

		}

	}