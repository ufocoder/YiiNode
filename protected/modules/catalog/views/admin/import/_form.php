<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'import-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        )
    ));

    $attr = 'category_id';
    $name = CHtml::resolveName($model, $attr);
    $updateId =CHtml::getIdByName($name);
    $containerId = 'openTree';
    $backgroundId = 'backgroudTree';
?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->dropDownListRow($model, 'data_provider', $model->values('data_provider', 'data'), array('encode' => false)); ?>
<div class="control-group">
    <?php echo $form->labelEx($model,'data_file', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->fileField($model, 'data_file'); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->labelEx($model,'category_id', array('class'=>'control-label')); ?>
    <div class="controls">
    <?php echo CHtml::activeHiddenField($model, 'category_id');?>
    <?php echo CHtml::link('Вне категорий', '#', array(
        'id' => 'openTree',
        'onClick' => 'return false;',
        'style' => 'width: 80%; display: block'
    ));?>

    <div id="dataTree"></div>

<?php
    $zTree = $this->widget('ext.ztree.zTreeDropdown',array(
        'containerId'=>$containerId,
        'updateId'=>$updateId,
        'backgroundId'=>$backgroundId,
        'treeNodeNameKey'=>'title',
        'treeNodeKey'=>'id',
        'treeNodeParentKey'=>'id_parent',
        'cssFile' => array('bootstrapStyle.css'),
        'options'=>array(
            'callback'=>array(
                'onClick' => "js:zTreeOnClick"
            )
        ),
        'data'=>$data_tree
    ));

    $script = "
    function zTreeOnClick(event, treeId, treeNode) {
        if (treeNode) {
            $('#{$containerId}').html(treeNode.name);
            $('#{$updateId}').val(treeNode.id);
            $('#{$backgroundId}').hide();
        }
    }
    ";

    Yii::app()->getClientScript()->registerCoreScript('jquery');
    Yii::app()->clientScript->registerScript("widget-zTree-".$zTree->id, $script, CClientScript::POS_READY);
 ?>


    </div>
</div>
<?php echo $form->dropDownListRow($model, 'product_action', $model->values('product_action', 'data'), array('encode' => false)); ?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'type'=>'primary', 'label'=>Yii::t('site', 'Import'), "htmlOptions"=>
    array(
      "onclick" => 'js:bootbox.confirm("Подтвердите что вы указали поставщика и выбрали нужный раздел каталога", function(confirmed){ if (confirmed) $("#'.$form->getId().'").submit();});'
    ))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>