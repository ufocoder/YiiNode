<?php
    $this->breadcrumbs = array(
        Yii::t('site', 'Catalog')=>array('/catalog'),
        Yii::t('catalog', 'Import')
    );

    $this->renderPartial('/_menu');
?>
<?php echo $this->renderPartial('/import/_form', array(
    'model' => $model,
    'data_tree' => $data_tree
)); ?>