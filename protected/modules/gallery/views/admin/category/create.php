<?php
    /* @var $this DefaultController */
    /* @var $model GalleryCategory */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t("site", "Create category");
    $this->breadcrumbs=array(
        Yii::t('site', 'Create')
    );
?>
<?php echo $this->renderPartial('/admin/category/_form', array('model'=>$model)); ?>