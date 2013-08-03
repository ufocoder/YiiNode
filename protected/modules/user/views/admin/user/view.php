<?php

    $this->title = Yii::t('site', 'View profile').' "'.$model->login.'"';
    $this->breadcrumbs = array(
        Yii::t('site', 'Users') => array('/admin/user'),
        $model->login,
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update user'), 'url'=>array('update', 'id'=>$model->id_user), 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Change password'), 'url'=>array('changepassword', 'id'=>$model->id_user), 'icon'=>'lock'),
    );
?>

<fieldset>
<legend><?php echo Yii::t('site', 'Account information');?></legend>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'login',
        'email',
        array(
            'name'=>'time_visited',
            'value'=> !empty($model->time_visited)?date("d.m.y, h:i", $model->time_visited):null,
        ),
        array(
            'name'=>'status',
            'value'=> !empty($model->status)?Yii::t("site", "Active"):Yii::t("site", "Not active"),
        ),
    ),
)); ?>

<?php
    $profile = $model->profile;
    $profileFields = $profile->getFields();
    if ($profileFields):
        $attributes = array();
        foreach($profileFields as $field){
            $value = null;
            if (CHtml::encode($field->range))
                $value = Profile::range($field->range,$profile->getAttribute($field->varname));
            else
                $value = $profile->getAttribute($field->varname);

            $attributes[] = array(
                'name' => $field->varname,
                'label' => CHtml::encode(Yii::t('site', $field->title)),
                'value' => $value
            );
        }

?>

<fieldset>
<legend><?php echo Yii::t('site', 'Profile information');?></legend>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=> $attributes,
)); ?>
</fieldset>
<?php endif; ?>