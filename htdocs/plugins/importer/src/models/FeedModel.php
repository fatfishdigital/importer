<?php
/**
 * Created by PhpStorm.
 * User: fatfish
 * Date: 13/4/18
 * Time: 9:30 AM
 */

namespace fatfish\importer\models;
use fatfish\importer\importer;
use Craft;
use craft\base\Model;

class FeedModel extends Model
{
    public $id;
    public $name;
    public $feedurl;
    public $feedtype=[
        'atom'=>'Atom',
        'csv'=>'CSV',
        'xml'=>'XML',
        'rss'=>'RSS',
        'json'=>'JSON',


    ];
    public $Entrytype;
    public $dateUpdated;

    public function rules()
    {
        return [
            ['id', 'integer'],
            ['name', 'string'],
            ['feedurl', 'string'],
            ['feedtype'],
            ['entrytype','string'],
            ['dateCreated', 'datetime'],
            ['dateUpdated','datetime'],
            ['uid','integer']
        ];
    }
public function __toString()
{
    return  Craft::t('importer',$this->name);

}
public function getFeedType()
{
return $this->feedtype;

}
public function getEntryType()
{
    $categories = Craft::$app->sections->getAllSections();
    $entries_type=[];
    foreach($categories as $category)
    {
        $entries_type[$category->id]=$category->handle;
    }
    return $entries_type;
}
    public function getallFeeds()
    {

    }
}