<?php
    $this->breadcrumbs=array(
        Yii::t('all', 'Simpleblock')=>array('index'),
        Yii::t('all', 'Manage')
    );

    $this->menu=array(
        array('label'=>Yii::t('all', 'List'), 'url'=>array('/block/default/index'), 'active'=>true)
    );

    Yii::app()->clientScript->registerScript('/block/search', "
        $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
        });
        $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('block-grid', {
                data: $(this).serialize()
            });
            return false;
        });
    ");
?>

<?php $this->renderPartial('/item/_search',array('model'=>$model)); ?>
<?php $this->renderPartial('/item/_grid',array('model'=>$model)); ?>