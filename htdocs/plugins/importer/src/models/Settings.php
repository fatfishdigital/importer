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
 * Importer Settings Model
 *
 * This is a model used to define the plugin's feedsettings.
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
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
  public $apiurl;
  public $apikey;
  public $entries;

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
    public function rules()
    {
        return [
            ['apiurl', 'string'],
            ['apikey', 'default', 'value' => 'API_KEY_HERE'],
            ['entries', 'string'],
        ];
    }
}
