<?php
namespace powerkernel\yiiproduct\controllers;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use powerkernel\yiilaundry\models\Order;
use powerkernel\yiiproduct\models\Product;
use powerkernel\yiiuser\models\User;


/**
 * Class AdminController
 */
class AdminController extends \powerkernel\yiicommon\controllers\ActiveController
{
    public $modelClass = '';

    /**
     * @inheritdoc
     * @return array
     */
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
                    'actions' => ['best-selling','latest-orders','hold-orders'],
                    'roles' => ['admin'],
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
                'best-selling' => ['POST'],
                'latest-orders' => ['POST'],
            ]
        );
    }

    /* Ashok Admin ~ 24-09-2018 */
    public function actionBestSelling(){
        $allOrder = Order::find()->select(['order_item'])->where(['order_status'=>"Delivered"])->asArray()->all();
    
        $newArray = array();
        if(!empty( $allOrder)){
            foreach($allOrder as $k=>$v){
                if(isset($v['order_item']) && $v['order_item'] !== ''){
                    if(!empty($v['order_item'])){
                        foreach($v['order_item'] as $vc){
                            $newArray[] = (string)$vc['product_id'];
                        }
                    }
                }
            }
        }else{
            return array("success"=>false,"errors"=>"No any order found");
        }
        // print_r($newArray);
        $vals = array_count_values($newArray);
        $getProducts = array();
        if(!empty($vals)){
            $i = 0;
            foreach($vals as $kk=>$vv){
                // if($i>10){
                //     break;
                // }
                $product =  Product::find()->where(["_id"=>$kk])->asArray()->one();
                unset($product['created_at']);
                unset($product['updated_at']);
                unset($product['created_by']);
                unset($product['updated_by']);
                $getProducts[$i] =  $product;
                $getProducts[$i]['_id'] =  (string)$product['_id'];
                $getProducts[$i]['count'] =  $vv;
                $i++;
            }
        }
        usort($getProducts, function (array $a, array $b) { return $b['price'] - $a['price']; });
        $getProducts = array_slice($getProducts, 0, 10, true);
        // usort($getProducts, function($a, $b) {
        //     return $a['count'] - $b['count'];
        // });
        return array("data"=>$getProducts,"count"=>array_sum($vals));
    }

    /* Ashok Admin Latest Order ~ 24-09-2018 */
    public function actionLatestOrders(){
        $allOrder = Order::find()->orderBy(['updated_at' => SORT_DESC])->limit(10)->all();
        return $allOrder;
    }
    public function actionHoldOrders(){
        $query = Order::find()->where(['payment_status'=>'Failed'])->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
       return $dataProvider;
    }
    
}
