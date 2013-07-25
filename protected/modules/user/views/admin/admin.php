<?php
    $this->breadcrumbs=array(
        Yii::t('site', 'Users')=>array('/user'),
        Yii::t('site', 'Manage'),
    );

    $this->menu = array(
        array('label'=>Yii::t('site', 'Create'), 'url'=>array('create')),
        array('label'=>Yii::t('site', 'User list'), 'url'=>array('admin'), "active"=>true),
    );

    Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });    
    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update('user-grid', {
            data: $(this).serialize()
        });
        return false;
    });
    ");

    $this->title = Yii::t('site', "Manage Users");
?>

<?php // echo $this->renderPartial('/admin/_search', array('model'=>$model)); ?>
<?php // echo $this->renderPartial('/admin/_grid', array('model'=>$model)); ?>