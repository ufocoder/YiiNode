<?php
    $this->title = Yii::t('slider', 'Update slide');
    $this->breadcrumbs=array(
        Yii::t('site', 'Template'),
        Yii::t('site', 'Slider')=>array('index'),
        $model->title=>array('view', 'id'=>$model->id_slider),
        Yii::t('site', 'Update')
    );

?>
<?php echo $this->renderPartial('/admin/item/_form', array('model'=>$model)); ?>