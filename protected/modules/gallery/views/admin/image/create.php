<?php
    /* @var $this DefaultController */
    /* @var $model GalleryImage */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t("site", "Create image");
    $this->breadcrumbs=array(
        Yii::t('site', 'Create')
    );
?>
<?php echo $this->renderPartial('/admin/image/_form', array('model'=>$model)); ?>