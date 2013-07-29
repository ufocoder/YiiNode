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
<legend><h4><?php echo Yii::t('site', 'Account information');?></h4></legend>
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


?>
<fieldset>
<legend><h4><?php echo Yii::t('site', 'Profile information');?></h4></legend>

    <?php 
        $profileFields=ProfileField::model()->forOwner()->sort()->findAll();
        if ($profileFields) {
            foreach($profileFields as $field) {
                //echo "<pre>"; print_r($profile); die();
            ?>
    <tr>
        <th class="label"><?php echo CHtml::encode(UserModule::t($field->title)); ?></th>
        <td><?php echo (($field->widgetView($profile))?$field->widgetView($profile):CHtml::encode((($field->range)?Profile::range($field->range,$profile->getAttribute($field->varname)):$profile->getAttribute($field->varname)))); ?></td>
    </tr>
            <?php
            }//$profile->getAttribute($field->varname)
        }
    ?>
</fieldset>