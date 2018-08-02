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
			$FeedMappingRecord  = new FeedMappingRecord();
	        $FeedMappingRecord->entries_field = $model->entries_field;
	        $FeedMappingRecord->mapped_field = $model->mapped_field;
	        $FeedMappingRecord->critearea    = $model->critearea;
	        $FeedMappingRecord->feed_type   = $model->feed_type;
	        $FeedMappingRecord->importer_feeds_id = $model->id;

	        $FeedMappingRecord->save();

			return true;

		}

	}