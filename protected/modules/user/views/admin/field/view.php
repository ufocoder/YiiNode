<?php
    $this->title = Yii::t('site', 'View profile field #').$model->varname;
    $this->breadcrumbs = array(
        Yii::t('site', 'Users') => array('/admin/user'),
        Yii::t('site', 'Profile fields') => array('/admin/user/admin'),
        Yii::t('site', $model->title),
    );
?>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id_user_field',
        'varname',
        'title',
        'field_type',
        'field_size',
        'field_size_min',
        'required',
        'match',
        'range',
        'error_message',
        'default',
        'position',
        array(
            'name' => 'visible',
            'value' => ProfileField::values ('visible', $model->visible)
        )
    ),
)); ?>
