<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26/3/18
 * Time: 4:13 PM
 */

namespace fatfish\importer\records;
use Craft;
use craft\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class ImporterRecord extends ActiveRecord
{


  public static function tableName():string
  {
      return '{{%importer_field_mapping}}';
  }
  public static function find()
  {
      return parent::find(); // TODO: Change the autogenerated stub
  }
}