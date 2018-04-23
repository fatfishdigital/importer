<?php
/**
 * importer plugin for Craft CMS 3.x
 *
 * content importer
 *
 * @link      Fatfish.com.au
 * @copyright Copyright (c) 2018 Fatfish
 */

namespace fatfish\importer;

use fatfish\importer\models\Settings;
use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\console\Application as ConsoleApplication;

use http\Url;
use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Fatfish
 * @package   Importer
 * @since     1.0.0
 *
 * @property  Settings $feedsettings
 * @method    Settings getSettings()
 */
class Importer extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Importer::$plugin
     *
     * @var Importer
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Importer::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;


        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace ='fatfish\importer\console\controllers';
        }
        // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'importer/contentimporter';
            }
        );
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger2'] = 'importer/contentimporter/view';
            }
        );


        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules['importer'] = 'importer/contentimporter/render';

        });
        Event::on(UrlManager::class,UrlManager::EVENT_REGISTER_CP_URL_RULES,function (RegisterUrlRulesEvent $event){
           $event->rules['contentimporter/cronview'] = 'importer/contentimporter/cronview';
        });
        Event::on(UrlManager::class,UrlManager::EVENT_REGISTER_CP_URL_RULES,function(RegisterUrlRulesEvent $event){
            $event->rules['contentimporter/feedsettings'] = 'importer/contentimporter/feedsettings';
        });
        Event::on(UrlManager::class,UrlManager::EVENT_REGISTER_CP_URL_RULES,function(RegisterUrlRulesEvent $event){
            $event->rules['contentimporter/feeds'] = 'importer/contentimporter/feeds';
        });
        Event::on(UrlManager::class,UrlManager::EVENT_REGISTER_CP_URL_RULES,function (RegisterUrlRulesEvent $event){
            $event->rules['contentimporter/newfeed'] = 'importer/contentimporter/newfeed';
        });
        Event::on(UrlManager::class,UrlManager::EVENT_REGISTER_CP_URL_RULES,function (RegisterUrlRulesEvent $event){

            $event->rules['contentimporter/editfeed/<feedId:\d+>'] = 'importer/contentimporter/editfeed';
            $event->rules['contentimporter/deletefeed/<feedId:\d+>'] = 'importer/contentimporter/deletefeed';


        });

      /**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'importer',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s feedsettings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered feedsettings HTML, which will be inserted into the content
     * block on the feedsettings page.
     *
     * @return string The rendered feedsettings HTML
     */
    protected function settingsHtml(): string
    {
        $categories = Craft::$app->sections->getAllSections();
        $entries_type=[];
        foreach($categories as $category)
        {
            $entries_type[$category->id]=$category->handle;
        }
        return Craft::$app->view->renderTemplate(
            'importer/settings',
            [
                'feedsettings' => $this->getSettings(),
                'categories'=>$entries_type
            ]
        );
    }



}

