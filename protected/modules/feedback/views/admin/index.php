<?php
    /* @var $this DefaultController */
    /* @var $model Feedback */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t('site', 'Manage feedback');
    $this->actions = array(
        array('label'=>Yii::t('site', 'Feedback list'), 'url'=> Yii::app()->createUrl('/admin/feedback/default/index'), 'icon' => 'list'),
        array('label'=>Yii::t('site', 'Settings'), 'url'=> Yii::app()->createUrl('/admin/feedback/default/setting'), 'icon' => 'cog'),
    );

    $this->breadcrumbs = array(
        Yii::t('site', 'Feedback'),
    );

?>

<?php echo $this->renderPartial('/admin/_grid', array('model'=>$model)); ?>
