<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */

namespace powerkernel\yiiproduct\v1\controllers;
use powerkernel\yiicommon\controllers\RestController;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
/**
 * Class DefaultController
 * @package powerkernel\yiipage\v1\controllers
 */
class DefaultController extends RestController
{
    
    public function actionIndex()
    {
      
        return[
            'success'=>true,
            'data'=>'Product Api'
        ];
    }
}