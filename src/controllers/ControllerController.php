<?php

namespace wavedesign\crafthrcommencementimportutility\controllers;

use Craft;
use craft\web\Controller;
use yii\web\Response;

/**
 * Controller controller
 */
class ControllerController extends Controller
{
    public $defaultAction = 'index';
    protected array|int|bool $allowAnonymous = self::ALLOW_ANONYMOUS_NEVER;

    /**
     * _hr-commencement-import-utility/controller action
     */
    public function actionIndex(): Response
    {
        return "test complete";
    }
}
