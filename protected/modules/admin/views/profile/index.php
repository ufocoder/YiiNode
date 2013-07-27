<?php
    /* @var $this ProfileController */
    /* @var $model User */

    $this->title = Yii::t('site', 'View');
    $this->breadcrumbs=array(
        Yii::t("site", "Profile")
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update profile'), 'url'=>array('update'), 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Change password'), 'url'=>array('changepassword'), 'icon'=>'lock'),
    );

    $this->title = Yii::t("site", "Your profile");
?>
<h4><?php echo Yii::t('site', '');?></h4>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'login',
        array(
            'name' => 'role',
            'value' => User::values('role', $model->role)
        ),
        'email',
        array(
            'name'=>'time_created',
            'value'=> !empty($model->time_created)?date("d.m.y, h:i", $model->time_created):null,
        ),
        array(
            'name'=>'time_updated',
            'value'=> !empty($model->time_updated)?date("d.m.y, h:i", $model->time_updated):null,
        ),
        array(
            'name'=>'time_visited',
            'value'=> !empty($model->time_visited)?date("d.m.y, h:i", $model->time_updated):null,
        ),
        array(
            'name'=>'status',
            'value'=> !empty($model->status)?Yii::t("site", "Active"):Yii::t("site", "Not active"),
        ),
    ),
)); ?>