<?php
/**
 * importer plugin for Craft CMS 3.x
 *
 * content importer
 *
 * @link      Fatfish.com.au
 * @copyright Copyright (c) 2018 Fatfish
 */

namespace fatfish\importer\models;

use fatfish\importer\Importer;

use Craft;
use craft\base\Model;

/**
 * ImporterModel Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Fatfish
 * @package   Importer
 * @since     1.0.0
 */
class ImporterModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some model attribute
     *
     * @var string
     */
    public $id;
    public $structureId;
    public $name;
    public $handle;
    public $type;
    public $enableVersioning;
    public $propagateEntries;
    public $dateCreated;
    public $dateUpdated;
    public $uid;
    public $importer_feeds_id;


    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public $entries_field;
    public $mapped_field;
    public $critearea;

    public function rules()
    {
        return [
            ['id', 'integer'],
            ['entries_field', 'integer'],
            ['mapped_field', 'string'],
            ['critearea', 'integer`'],
            ['dateCreated', 'datetime'],
            ['dateUpdated','datetime'],
            ['uid','integer'],
            ['importer_feeds_id','integer']
        ];
    }


}
