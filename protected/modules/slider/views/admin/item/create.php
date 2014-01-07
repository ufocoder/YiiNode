<?php
    $this->title = Yii::t('slider', 'Add slide');
    $this->breadcrumbs=array(
        Yii::t('site', 'Template'),
        Yii::t('site', 'Slider')=>array('index'),
        Yii::t('slider', 'Add slide')
    );
    $this->renderPartial('/admin/menu');
?>
<?php echo $this->renderPartial('/admin/item/_form', array('model'=>$model)); ?>