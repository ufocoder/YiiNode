<?php
    /* @var $this ArticlesController */
    /* @var $model Article */

    $nodeId = Yii::app()->getNodeId();

    $this->breadcrumbs=array(
        Yii::t('site', 'Articles list') => Yii::app()->createUrl('/admin/node/'.$nodeId),
        Yii::t('site', 'Create')
    );

    $this->title = Yii::t("site", "Create article");
?>

<?php echo $this->renderPartial('/admin/_form', array('model'=>$model)); ?>