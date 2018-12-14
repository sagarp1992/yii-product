<?php

namespace powerkernel\yiiproduct\models;

use Yii;
use powerkernel\yiiproduct\models\Product;
use powerkernel\yiicommon\behaviors\UTCDateTimeBehavior;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form of `common\models\PaypalIpn`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'description',
            'short_description',
            'price',
            'image_url',
            'thumb_url',
            'status',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at'
        ];
    }
    public function search($params)
    {
        $this->load($params);
        $query = Product::find();
        $query->orFilterWhere(['like', 'description', $this->description])
            ->orFilterWhere(['like', 'name', $this->name])
            ->orFilterWhere(['like', 'status', $this->status])
            ->orFilterWhere(['like', 'short_description', $this->short_description])
            ->orFilterWhere(['like', 'price', $this->price]);
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
        ]);
        return $dataProvider;
    }
}