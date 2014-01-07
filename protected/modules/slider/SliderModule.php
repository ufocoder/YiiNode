<?php

class SliderModule extends WebModule
{
   /**
     * Flag module is node type
     */
    public $nodeModule = false;

    /**
     * Module initialize
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Returns the description of this module.
     * @return string the description of this module.
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Returns the version of this module.
     * The default implementation returns '1.0'.
     * You may override this method to customize the version of this module.
     * @return string the version of this module.
     */
    public function getVersion()
    {
        return '1.0';
    }

    public function initColorpicker()
    {
        $assets = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets');

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCssFile($assets . '/css/colorpicker.css');
        $cs->registerScriptFile($assets . '/js/colorpicker.js');
        $cs->registerScriptFile($assets . '/js/eye.js');
        $cs->registerScriptFile($assets . '/js/utils.js');

    }
}