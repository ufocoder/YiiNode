<?php
    /* @var $this ContactController */
    /* @var $model Contact */

    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('default/view', array('id'=>$model->id_contact, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('default/delete', array('id'=>$model->id_contact, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update contact");
    $this->breadcrumbs=array(
        Yii::t('site', 'Contact #{id}', array('{id}'=>$model->id_contact)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View contact'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete contact'), 'url' => Yii::app()->createUrl('default/delete', array('id'=>$model->id_contact, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<?php echo $this->renderPartial('/admin/_form', array('model'=>$model)); ?>