<?php
    /* @var $this DefaultController */
    /* @var $model RedirectSetting */

    $nodeId = Yii::app()->getNodeId();
    $this->title = Yii::t("site", "Redirect settings");
    $this->breadcrumbs=array(
        Yii::t('site', 'Settings'),
    );
?>
<?php echo $this->renderPartial('/admin/_setting', array('model'=>$model)); ?>