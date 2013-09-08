<?php

    $this->title = Yii::t("site", "Settings");
    $this->breadcrumbs=array(
        Yii::t('site', 'Settings')
    );
?>
<?php echo $this->renderPartial('/admin/setting/_form', array('model'=>$model)); ?>