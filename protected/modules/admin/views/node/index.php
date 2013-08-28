<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $this->title = Yii::t('site', 'Node list');
    $this->breadcrumbs = array(
        Yii::t('site', 'Structure'),
    );
?>

<?php echo $this->renderPartial('/node/_grid', array(
    'model' => $model
)); ?>
