<?php

    $this->title = Yii::t('order', 'Settings');
    $this->breadcrumbs=array(
        Yii::t('order', 'Orders')=>Yii::app()->createUrl("/admin/order/default/index"),
        Yii::t('order', 'Settings')
    );

    $this->renderPartial('/admin/_menu');


?>
<?php echo $this->renderPartial('/admin/setting/_form', array('model'=>$model)); ?>