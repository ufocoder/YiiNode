<?php

/**
 * Order module.
 */
class OrderModule extends WebModule
{
    /**
     * Flag module is node type
     */
    public $nodeModule = true;

    /**
     * Module initialization
     */
    public function init()
    {
        parent::init();

        self::publishAssets();
    }

    public static function publishAssets()
    {
        $path = Yii::getpathOfAlias('application.modules.order.assets');
        $assets = Yii::app()->assetManager->publish($path, false, -1, true);
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($assets . '/order.js');
        $cs->registerScript("order-url", "var url_basket='/order/basket';", CClientScript::POS_BEGIN);
    }
}