<?php
    /* @var $this ArticlesController */
    /* @var $model Article */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t("site", "Create article");
    $this->breadcrumbs=array(
        Yii::t('site', 'Create')
    );
?>
<?php echo $this->renderPartial('/admin/article/_form', array('model'=>$model)); ?>