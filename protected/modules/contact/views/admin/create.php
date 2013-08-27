<?php
    /* @var $this ContactController */
    /* @var $model Contact */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t("site", "Create contact");
    $this->breadcrumbs=array(
        Yii::t('site', 'Create')
    );
?>
<?php echo $this->renderPartial('/admin/_form', array('model'=>$model)); ?>