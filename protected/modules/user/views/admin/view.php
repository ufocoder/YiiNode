<?php

    $this->breadcrumbs=array(
        Yii::t('site', 'Users')=>array('admin'),
        $model->username,
    );

    $this->menu=array(
        array('label'=>Yii::t('site', 'Create'), 'url'=>array('create')),
        array('label'=>Yii::t('site', 'List User'), 'url'=>array('admin')),
        array('label'=>Yii::t('site', 'Update User'), 'url'=>array('update','id'=>$model->id)),
        array('label'=>Yii::t('site', 'Delete User'), 'url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('site', 'Are you sure to delete this item?'))),
    );

    $this->title = Yii::t('site', "Manage Users");

?>

<h3><?php echo Yii::t('site', 'View User').' "'.$model->username.'"'; ?></h3>
<?php 
    $attributes = array(
        'id',
        'username',
    );

    $profileFields=ProfileField::model()->forOwner()->sort()->findAll();
    if ($profileFields) {
        foreach($profileFields as $field) {
            array_push($attributes,array(
                    'label' => Yii::t('site', $field->title),
                    'name' => $field->varname,
                    'type'=>'raw',
                    'value' => (($field->widgetView($model->profile))?$field->widgetView($model->profile):(($field->range)?Profile::range($field->range,$model->profile->getAttribute($field->varname)):$model->profile->getAttribute($field->varname))),
                ));
        }
    }

    array_push($attributes,
        'email',
        'activkey',
        'create_at',
        'lastvisit_at',
        array(
            'name' => 'superuser',
            'value' => User::itemAlias("AdminStatus",$model->superuser),
        ),
        array(
            'name' => 'status',
            'value' => User::itemAlias("UserStatus",$model->status),
        )
    );

    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>$model,
        'attributes'=>$attributes,
    ));

?>
