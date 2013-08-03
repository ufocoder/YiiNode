<?php
    $this->title = Yii::t('site', "Create user");
    $this->breadcrumbs = array(
        Yii::t('site', 'Users')=>array('/admin/user'),
        Yii::t('site', 'Create'),
    );
?>
<?php echo $this->renderPartial('/admin/user/_form', array('model'=>$model,'profile'=>$profile));?>