<?php
/**
 * Created by PhpStorm.
 * User: fatfish
 * Date: 19/4/18
 * Time: 5:10 PM
 */

namespace fatfish\importer\records;
use Craft;

use yii\db\ActiveRecord;

class FeedMappingRecord extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName():string
    {
        return '{{%importer_feed_mapping}}'; // TODO: Change the autogenerated stub
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find(); // TODO: Change the autogenerated stub
    }

}