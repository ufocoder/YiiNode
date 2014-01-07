<?php
    $this->title = Yii::t('site', 'Settings');
    $this->breadcrumbs = array(
        Yii::t('site', 'Template'),
        Yii::t('site', 'Slider')=>array('index'),
        Yii::t('site', 'Settings')
    );

    $this->renderPartial('/admin/menu');
?>
<?php echo $this->renderPartial('/admin/setting/_form', array('model'=>$model)); ?>