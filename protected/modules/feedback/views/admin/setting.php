<?php
    /* @var $this DefaultController */
    /* @var $model FeedbackSetting */

    $nodeId = Yii::app()->getNodeId();
    $this->title = Yii::t("site", "Feedback settings");
    $this->breadcrumbs = array(
        Yii::t('site', 'Feedback') => array('/admin/feedback/default/index'),
        Yii::t('site', 'Settings'),
    );
        $this->actions = array(
        array('label'=>Yii::t('site', 'Feedback list'), 'url'=> Yii::app()->createUrl('/admin/feedback/default/index'), 'icon' => 'list'),
        array('label'=>Yii::t('site', 'Settings'), 'url'=> Yii::app()->createUrl('/admin/feedback/default/setting'), 'icon' => 'cog'),
    );

?>
<?php echo $this->renderPartial('/admin/_setting', array('model'=>$model)); ?>