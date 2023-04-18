<?php
/**
 * RegistrarImporterCraft plugin for Craft CMS 3.x
 *
 * A plugin for importing Excel files
 *
 * @link      https://tdlacct.github.io/
 * @copyright Copyright (c) 2023 T Luce
 */

namespace wave\registrarimportercraft\console\controllers;

use wave\registrarimportercraft\RegistrarImporterCraft;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Services;  

/**
 * Default Command
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
 * ./craft registrar-importer-craft/default
 *
 * Actions must be in 'kebab-case' so actionDoSomething() maps to 'do-something',
 * and would be invoked via:
 *
 * ./craft registrar-importer-craft/default/do-something
 *
 * @author    T Luce
 * @package   RegistrarImporterCraft
 * @since     1.0.0
 */
class DefaultController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Handle registrar-importer-craft/default console commands
     *
     * The first line of this method docblock is displayed as the description
     * of the Console Command in ./craft help
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'something';

        echo "Welcome to the console DefaultController actionIndex() method\n";

        return $result;
    }

    /**
     * Handle registrar-importer-craft/default/do-something console commands
     *
     * The first line of this method docblock is displayed as the description
     * of the Console Command in ./craft help
     *
     * @return mixed
     */
    public function actionDoSomething()
    {
        $result = 'something';

        echo "Welcome to the console DefaultController actionDoSomething() method\n";

        return $result;
    }

    public function actionConvert(string $filepath = "/var/www/web/php/Sum22.xlsx") {

        $services = RegistrarImporterCraft::getInstance()->CraftService;
        $result = $services->multiSortComm($services->excelToArray($filepath));
        file_put_contents("/var/www/web/dev/registrarimportercraft/src/console/controllers/output_sum22_sorted_new.html", $result);
        //echo $result;
    }

    
}
