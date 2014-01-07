<?php
    $this->title = Yii::t('slider', 'Slide list');
    $this->breadcrumbs = array(
        Yii::t('site', 'Template'),
        Yii::t('site', 'Slider')=>array('index'),
        Yii::t('slider', 'Slide list')
    );
    $this->renderPartial('/admin/menu');
?>
<?php echo $this->renderPartial('/admin/item/_grid', array('model'=>$model)); ?>