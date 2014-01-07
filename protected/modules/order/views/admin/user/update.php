<?php
    $this->title = Yii::t('catalog', 'Discount');
    $this->breadcrumbs=array(
        Yii::t('order', 'Orders')=>Yii::app()->createUrl("/admin/order/default/index"),
        Yii::t('order', 'Clients') => Yii::app()->createUrl("/admin/order/user/index"),
        Yii::t('catalog', 'Discount')
    );

    $this->renderPartial('/admin/_menu');

?>
<?php echo $this->renderPartial('/admin/user/_form', array('model'=>$model)); ?>