<?php

class m180831_122133_product extends \yii\mongodb\Migration
{
    
    public function up()
    {
        $col = Yii::$app->mongodb->getCollection('product_db');
    }

    public function down()
    {
        echo "m180831_122133_product cannot be reverted.\n";

        return false;
    }
}
