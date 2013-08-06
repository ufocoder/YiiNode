<?php

class ElFinderWidget extends CWidget
{
    public $settings = array();
    public $connectorRoute = false;

    public function init()
    {
        if (empty($this->connectorRoute))
            throw new CException('$connectorRoute must be set!');

        $assets = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets');

        if (Yii::app()->getRequest()->enableCsrfValidation) {
            Yii::app()->clientScript->registerMetaTag(Yii::app()->request->csrfToken, 'csrf-token');
            Yii::app()->clientScript->registerMetaTag(Yii::app()->request->csrfTokenName, 'csrf-param');
        }

        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($cs->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('jquery.ui');
        $cs->registerCssFile($assets . '/css/elfinder.min.css');
        $cs->registerCssFile($assets . '/css/theme.css');
        $cs->registerScriptFile($assets . '/js/elfinder.min.js');

        $langs = array('ar', 'bg', 'ca', 'cs', 'de', 'es', 'fr', 'hu', 'jp', 'nl', 'no', 'pl', 'pt_BR', 'ru', 'zh_CN');

        $lang = Yii::app()->language;
        if (!in_array($lang, $langs)){
            $lang = current(explode('_', $lang));
            if (!in_array($lang, $langs))
                $cs->registerScriptFile($assets . '/js/i18n/elfinder.' . $lang . '.js');
        }

        $this->settings['url'] = Yii::app()->createUrl($this->connectorRoute);
        $this->settings['lang'] = Yii::app()->language;
    }

    public function run()
    {
        $id = $this->getId();
        $settings = CJavaScript::encode($this->settings);
        $cs = Yii::app()->getClientScript();
        $cs->registerScript('elFinder', "$('#".$id."').elfinder($settings);");
        echo "<div id=\"".$id."\"></div>";
    }

}
