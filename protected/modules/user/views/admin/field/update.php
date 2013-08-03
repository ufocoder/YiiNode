<?php
    $this->title = Yii::t('site', 'Update profile field #').$model->varname;
    $this->breadcrumbs = array(
        Yii::t('site', 'Users') => array('/admin/user'),
        Yii::t('site', 'Profile fields') => array('/admin/user/field'),
        $model->title => array('view','id' => $model->id_user_field),
        Yii::t('site', 'Update')
    );
?>

<?php echo $this->renderPartial('/admin/field/_form', array('model'=>$model)); ?>