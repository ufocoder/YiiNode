<?php
    $this->breadcrumbs=array(
        (Yii::t('site', 'Users'))=>array('admin'),
        $model->username=>array('view','id'=>$model->id),
        (Yii::t('site', 'Update User')),
    );
    $this->menu=array(
        array('label'=>Yii::t('site', 'Create'), 'url'=>array('create')),
        array('label'=>Yii::t('site', 'List User'), 'url'=>array('admin')),
        array('label'=>Yii::t('site', 'View User'), 'url'=>array('view','id'=>$model->id)),
        
    );

    $this->title = Yii::t('site', "Manage Users");
?>

<h3><?php echo Yii::t('site', 'Update User').": ".$model->username; ?></h3>
<?php echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile));?>