<?php
/**
 * RegistrarImporterCraft plugin for Craft CMS 3.x
 *
 * A plugin for importing Excel files
 *
 * @link      https://tdlacct.github.io/
 * @copyright Copyright (c) 2023 T Luce
 */

namespace wave\registrarimportercraft\controllers;

use wave\registrarimportercraft\RegistrarImporterCraft;

use Craft;
use craft\web\Controller;
use Craft\web\Request;

use craft\base\Element;
use craft\elements\Asset;
use craft\errors\UploadFailedException;
use craft\fields\Assets as AssetsField;
use craft\helpers\App;
use craft\helpers\ArrayHelper;
use craft\helpers\Assets;
use craft\helpers\Db;
use craft\helpers\Image;
use craft\helpers\StringHelper;
use craft\helpers\UrlHelper;
use craft\i18n\Formatter;
use craft\image\Raster;
use craft\models\VolumeFolder;
use craft\web\UploadedFile;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\base\UserException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use ZipArchive;
/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    T Luce
 * @package   RegistrarImporterCraft
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|int|bool $allowAnonymous = ['index', 'do-something', 'simple','file'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/registrar-importer-craft/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/registrar-importer-craft/default/do-something
     *
     * @return mixed
     */
    public function actionDoSomething()
    {
        $result = 'Welcome to the DefaultController actionDoSomething() method';

        return $result;
    }

    public function actionConvert(string $path) {
        //$path = Craft::$app->request->getBodyParam('filepath');
        
        $services = RegistrarImporterCraft::getInstance()->CraftService;
        $result = $services->excelToArray($path);

        return $result;
    }

    public function actionSort() {
        $data = Craft::$app->request->getBodyParam('data');
        $title = Craft::$app->request->getBodyParam('title');
        $mode = Craft::$app->request->getBodyParam('datamode');
        $services = RegistrarImporterCraft::getInstance()->CraftService;

        if ($mode == "honor-roll") {
            $htmloutput = $services->sortHonorRoll($data,$title,false);
            $htmloutput_readable =  htmlspecialchars($htmloutput);
            $press_release = $services->sortHonorRoll($data,$title,true);

            return json_encode([$htmloutput_readable,$htmloutput,$press_release,$mode]);
        } else {
            $htmloutput = $services->multiSortComm($data,false);
            $htmloutput_readable =  htmlspecialchars($htmloutput);
            $press_release = $services->multiSortComm($data,true);

            return json_encode([$htmloutput_readable,$htmloutput,$press_release,$mode]);
        }
    }

    public function actionFile() {
        $body = Craft::$app->request->getBodyParam('filepath');
        echo $body;
    }

    public function actionExcelUpload() {
        $file = Craft::$app->request->getBodyParam('file');
        $folder = Craft::$app->request->getBodyParam('folder');
        $services = RegistrarImporterCraft::getInstance()->CraftService;
        $result = $services->uploadNewAsset($file,$folder);
    }

    public function actionParam() {
        $uploadedFile = UploadedFile::getInstanceByName('assets-upload');

        if (!$uploadedFile) {
            return('No file was uploaded');
        }

        $folderId = (int)$this->request->getBodyParam('folderId') ?: null;
        $fieldId = (int)$this->request->getBodyParam('fieldId') ?: null;



        return($this->request->getBodyParam('folderId').$this->request->getBodyParam('fieldId'));

    }
    
    
}
