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
<fieldset>
<legend><?php echo Yii::t('site', 'Account information');?></legend>
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

<?php
    $profile = $model->profile;
    $profileFields = $profile->getFields();
    if ($profileFields):
        $attributes = array();
        foreach($profileFields as $field){
            $value = null;

            if ($field->widgetView($profile))
                $value = $field->widgetView($profile);
            elseif (CHtml::encode($field->range))
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