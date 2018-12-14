<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */


namespace powerkernel\yiiproduct\controllers;

use yii\filters\AccessControl;
use powerkernel\yiiproduct\models\Category;
use yii\data\ActiveDataProvider;
/**
 * Class CategoryController
 */
class CategoryController extends \powerkernel\yiicommon\controllers\ActiveController
{
    public $modelClass = 'powerkernel\yiiproduct\models\Category';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            '__class' => AccessControl::class,
            'rules' => [
                [
                    'verbs' => ['OPTIONS'],
                    'allow' => true,
                ],
                [
                    'actions' => ['index','update', 'create', 'delete','view','image-upload'],
                    'roles' => ['admin'],
                    'allow' => true,
                ],
                [
                    'actions' => ['category-list'],
                    'roles' => ['@'],
                    'allow' => true,
                ],
            ],
        ];
        return $behaviors;
    }

    protected function verbs()
    {
        $parents = parent::verbs();
        return array_merge(
            $parents,
            [
                'index' => ['GET'],
                'create' => ['POST'],
                'update' => ['POST'],
                'delete' => ['GET'],
                'view' => ['POST'],
                'image-upload' => ['POST'],
            ]
        );
    }
    //##### For member ##############
    public function actionCategoryList($all=false){ 
        
        $query = Category::find()->where(['status'=>'STATUS_ACTIVE'])->orderBy([
            'created_at'=>SORT_ASC
        ]);        
        if($all){
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }
       
        return $dataProvider;
    }
    public function actionImageUpload(){
        $data = \Yii::$app->getRequest()->getParsedBody();
        $array_thumb = array( 
            "eager" => array(
                array("width" => 30, "height" => 30)));
        $image = \Yii::$app->cloudinary->upload($data['image'],$array_thumb);       
        return $image;
    }

}
