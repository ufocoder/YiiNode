<?php
    /* @var $this ContactController */
    /* @var $model Contact */

    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('/admin/feedback/default/view', array('id'=>$model->id_feedback));
    $deleteUrl = Yii::app()->createUrl('/admin/feedback/default/delete', array('id'=>$model->id_feedback));

    $this->title = Yii::t("site", "Update feedback");
    $this->breadcrumbs=array(
        Yii::t('site', 'Feedback #{id}', array('{id}'=>$model->id_feedback)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View feedback'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete feedback'), 'url' => Yii::app()->createUrl('default/delete', array('id'=>$model->id_feedback, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<?php echo $this->renderPartial('/admin/_form', array('model'=>$model)); ?>