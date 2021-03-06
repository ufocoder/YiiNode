<?php

    $this->title = Yii::t('order', 'Order list');
    $this->breadcrumbs=array(
        Yii::t('order', 'Orders')=>Yii::app()->createUrl("/admin/order/default/index"),
        Yii::t('order', 'Manage')
    );

    $this->renderPartial('/admin/_menu');

    Yii::app()->clientScript->registerScript('search', "
        $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
        });
        $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('feedback-grid', {
                data: $(this).serialize()
            });
            return false;
        });
    ");
?>

<?php echo $this->renderPartial('/admin/order/_grid', array('model'=>$model)); ?>