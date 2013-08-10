<?php
    /* @var $this ProfileController */
    /* @var $model User */

    $this->title = Yii::t('site', 'Settings');
    $this->breadcrumbs=array(
        Yii::t("site", "Settings")
    );
?>
<?php $this->renderPartial('/settings/main/site', array('model'=>$model)); ?>