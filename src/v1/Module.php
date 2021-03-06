<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */

namespace powerkernel\yiiproduct\v1;
class Module extends \yii\base\Module
{

    public $controllerNamespace = 'powerkernel\yiiproduct\v1\controllers';
    public $defaultRoute = 'default';

    public function init()
    {
        
        parent::init(); // TODO: Change the autogenerated stub
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $class = 'powerkernel\yiicommon\i18n\MongoDbMessageSource';
        \Yii::$app->i18n->translations['product'] = [
            '__class' => $class,
            'on missingTranslation' => function ($event) {
                $event->sender->handleMissingTranslation($event);
            },
        ];
    }

    public static function t($message, $params = [], $language = null)
    {
        return \Yii::$app->getModule('product')->translate($message, $params, $language);
    }

    public static function translate($message, $params = [], $language = null)
    {
        return \Yii::t('product', $message, $params, $language);
    }

}