<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'setting-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl('default/index', array('nodeAdmin'=>true, 'nodeId'=> Yii::app()->getNodeId())),
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        ),
    ));


    $nodes = CHtml::listData(Node::model()->tree()->findAll(), 'id_node', 'title');

?>
<?php echo $form->errorSummary($model); ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'nodeId', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php $this->widget('bootstrap.widgets.TbSelect2',array(
            'model' => $model,
            'attribute' => 'nodeId',
            'asDropDownList' => true,
            'options' => array(
                'placeholder' => Yii::t("site", "Select a node"),
                'width' => '328px',
            ),
            'val' => null,
            'data' => $nodes,
        ));
        ?>
    </div>
</div>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Save'))); ?>
</div>

<?php $this->endWidget(); ?>