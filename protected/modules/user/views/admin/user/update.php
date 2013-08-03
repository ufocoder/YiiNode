<?php
    $this->title = Yii::t('site', 'Update user').": ".$model->login;
    $this->breadcrumbs = array(
        (Yii::t('site', 'Users'))=>array('/user/admin'),
        CHtml::encode($model->login) => array('view','id'=>$model->id_user),
        (Yii::t('site', 'Update')),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View user'), 'url'=>array('index', 'id'=>$model->id_user), 'icon'=>'user'),
        array('label'=>Yii::t('site', 'Change password'), 'url'=>array('changepassword', 'id'=>$model->id_user), 'icon'=>'lock'),
    );

?>

<?php echo $this->renderPartial('/admin/user/_form', array(
    'model' => $model,
    'profile' => $profile));
?>