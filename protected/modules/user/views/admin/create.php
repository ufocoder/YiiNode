<?php
    $this->breadcrumbs=array(
        Yii::t('site', 'Users')=>array('admin'),
        Yii::t('site', 'Create'),
    );

    $this->menu=array(
        array('label'=>Yii::t('site', 'Create'), 'url'=>array('create'), "active" => true),
        array('label'=>Yii::t('site', 'List User'), 'url'=>array('admin')),
    );

    $this->title = Yii::t('site', "Manage Users");
?>

<h3><?php echo Yii::t('site', "Create"); ?></h3>
<?php echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile));?>