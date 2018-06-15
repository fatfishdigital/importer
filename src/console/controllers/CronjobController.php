<?php
/**
 * CornJobs plugin for Craft CMS 3.x
 *
 * CronJobs
 *
 * @link      github.com/sailendraw
 * @copyright Copyright (c) 2018 Fatfish
 */

namespace fatfish\importer\console\controllers;

use fatfish\importer\console\controllers\CornJobs;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;
use fatfish\importer\controllers\ContentimporterController as Content;

/**
 * Cronjob Command
 *
 * The first line of this class docblock is displayed as the description
 * of the Console Command in ./craft help
 *
 * Craft can be invoked via commandline console by using the `./craft` command
 * from the project root.
 *
 * Console Commands are just controllers that are invoked to handle console
 * actions. The segment routing is plugin-name/controller-name/action-name
 *
 * The actionIndex() method is what is executed if no sub-commands are supplied, e.g.:
 *
 * ./craft corn-jobs/cronjob
 *
 * Actions must be in 'kebab-case' so actionDoSomething() maps to 'do-something',
 * and would be invoked via:
 *
 * ./craft corn-jobs/cronjob/do-something
 *
 * @author    Fatfish
 * @package   CornJobs
 * @since     1
 */
class CronjobController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Handle corn-jobs/cronjob console commands
     *
     * This will insert Data into Database
     * based on cron set
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $job = new Content(null,null);
        $job->actionRunCron();

    }


}
