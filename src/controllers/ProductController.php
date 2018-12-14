<?php

namespace powerkernel\yiiproduct\controllers;
use yii\filters\AccessControl;
use powerkernel\yiiproduct\models\Product;

use yii\data\Pagination;
use yii\data\Sort;
use yii\data\ActiveDataProvider;

/**
 * Class ProductController
 */
class ProductController extends \powerkernel\yiicommon\controllers\ActiveController
{
    public $modelClass = 'powerkernel\yiiproduct\models\Product';
  
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
                    'actions' => [ 'update', 'create', 'delete','image-upload','view','index'],
                    'roles' => ['admin'],
                    'allow' => true,
                ],
                [
                    'actions' => [ 'product-list','view'],
                    'roles' => ['@'],
                    'allow' => true,
                ],
            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        
        return $actions;
    }
    
    public function prepareDataProvider()
    {
        $searchModel = new \powerkernel\yiiproduct\models\ProductSearch();    
        return $searchModel->search(\Yii::$app->request->queryParams);
    }

    protected function verbs()
    {
        $parents = parent::verbs();
        return array_merge(
            $parents,
            [
                'index' => ['GET'],
                'product-list' => ['GET'],
                'create' => ['POST'],
                'update' => ['POST'],
                'delete' => ['GET'],
                'view' => ['POST'],
                'image-upload' => ['POST'],
            ]
        );
    }
    //##### For member ##############
    public function actionProductList($category_id=""){ 
        if($category_id == ""){
            $query = Product::find()->select(['name','description','short_description','image_url','thumb_url','price','profile','category_id'])
            ->where(['status'=>'STATUS_ACTIVE'])
            ->orderBy([
                'created_at'=>SORT_ASC
              ]);;
        }else{
            $query = Product::find()->select(['name','description','short_description','image_url','thumb_url','price','profile','category_id'])
            ->where(['category_id'=>$category_id,'status'=>'STATUS_ACTIVE'])
            ->orderBy([
                'created_at'=>SORT_ASC
            ]);;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
       return $dataProvider;
    }
    public function actionImageUpload(){
        $data = \Yii::$app->getRequest()->getParsedBody();
        $array_thumb = array( 
            "eager" => array(
                array("width" => 200, "height" => 200, "crop" => "fit")));
        $image = \Yii::$app->cloudinary->upload($data['image'],$array_thumb);
       
        return $image;
    }
}
