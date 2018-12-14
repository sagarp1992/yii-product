<?php

class m180903_053034_category extends \yii\mongodb\Migration
{
    public function up()
    {
        $col = Yii::$app->mongodb->getCollection('category_db');
    }

    public function down()
    {
        echo "m180903_053034_category cannot be reverted.\n";

        return false;
    }
}
