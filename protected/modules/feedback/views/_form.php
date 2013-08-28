<?php
    /* @var $this DefaultController */
    /* @var $model Article */
    /* @var $form BootActiveForm */

    $nodeId = Yii::app()->getNodeId();
    $action = Yii::app()->createUrl('feedback/default/index', array('nodeId'=>$nodeId));

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'feedback-form',
        'action' => $action,
        'method'=>'post',
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data',
            'class' => 'well'
        ),
    ));
?>

<div class="feedback-form form">
    <?php echo $form->errorSummary($model); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'person_name'); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'person_name'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'contact_phone'); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'contact_phone'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'contact_email'); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'contact_email'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'content'); ?>
        <div class="controls">
            <?php echo $form->textArea($model, 'content', array('class'=>'col_12')); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'verifyCode'); ?>
        <div class="value">
            <?php $this->widget('CCaptcha', array(
                'captchaAction' => Yii::app()->createUrl('feedback/default/captcha', array('nodeId'=>$nodeId)),
                'showRefreshButton'=>false,
                'clickableImage' =>true,
                'imageOptions' => array(
                    'class' => 'captcha',
                ))
            );
            ?>
            <br>
            <?php echo CHtml::activeTextField($model, 'verifyCode');?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn big"><?php echo Yii::t('site', 'Submit')?></button>
    </div>
</div>

<?php $this->endWidget(); ?>