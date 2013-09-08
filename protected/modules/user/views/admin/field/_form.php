<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'field-form',
        'type'=>'horizontal',
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        )
    ));

    $cs = Yii::app()->getClientScript();
    $cs->registerCoreScript('jquery');

    $cs->registerScript("field-visible","

    var fieldType = {
        'INTEGER':{
            'hide':['match'],
            'val':{
                'field_size':10,
                'default':'0',
                'range':''
            }
        },
        'VARCHAR':{
            'hide':[],
            'val':{
                'field_size':255,
                'default':'255',
                'range':'',
            }
        },
        'TEXT':{
            'hide':['field_size','range'],
            'val':{
                'field_size':0,
                'default':'',
                'range':''
            }
        },
        'DATE':{
            'hide':['field_size','field_size_min','match','range'],
            'val':{
                'field_size':0,
                'default':'0000-00-00',
                'range':''
            }
        },
        'FLOAT':{
            'hide':['match'],
            'val':{
                'field_size':'10.2',
                'default':'0.00',
                'range':''
            }
        },
        'DECIMAL':{
            'hide':['match'],
            'val':{
                'field_size':'10,2',
                'default':'0',
                'range':''
            }
        },
        'BOOL':{
            'hide':['field_size','field_size_min','match'],
            'val':{
                'field_size':0,
                'default':0,
                'range':'1==".Yii::t('site', 'Yes').";0==".Yii::t('site', 'No')."'
            }
        },
        'BLOB':{
            'hide':['field_size','field_size_min','match'],
            'val':{
                'field_size':0,
                'default':'',
                'range':''
            }
        },
        'BINARY':{
            'hide':['field_size','field_size_min','match'],
            'val':{
                'field_size':0,
                'default':'',
                'range':''
            }
        }
    };


    function setFields(type) {

        if (fieldType[type]){
            $('div[field]')
                .addClass('field-show')
                .removeClass('field-hide');

            if (fieldType[type].hide.length)
                $('div[field=' + fieldType[type].hide.join('], div[field=') + ']')
                    .addClass('field-hide')
                    .removeClass('field-show');

            $('div[field].field-show').show(500);
            $('div[field].field-hide').hide(500);

            ".($model->isNewRecord?"
            for (var t in fieldType[type].val) {
                $('div[field=\"' + t + '\"] input').val(fieldType[type].val[t]);
            }":null)."
        }
    }

    $('#field_type').change(function() {
        setFields($(this).val());
    });

    setFields($('#field_type').val());");
?>
    <?php echo $form->errorSummary($model); ?>

    <?php echo CHtml::tag("div", array('field'=>'varname'), $form->textFieldRow($model, 'varname', array('class'=>'span4', 'hint'=>Yii::t('site', "Allowed lowercase letters and digits."), 'readonly'=>(!empty($model->id_user_field)?true:false)))); ?>
    <?php echo CHtml::tag("div", array('field'=>'title'), $form->textFieldRow($model, 'title', array('class'=>'span4', 'hint'=>Yii::t('site', 'Field name on the language of "sourceLanguage".')))); ?>

    <?php if (!empty($model->id_user_field)):?>
        <?php echo CHtml::tag("div", array('field'=>'field_type'), $form->textFieldRow($model, 'field_type', array('class'=>'span4', 'hint'=>Yii::t('site', 'Field type column in the database.'), 'readonly'=>true, 'id'=>'field_type'))); ?>
    <?php else: ?>
        <?php echo CHtml::tag("div", array('field'=>'field_type'), $form->dropDownListRow($model, 'field_type', ProfileField::values('field_type'), array('class'=>'span4', 'id'=>'field_type'))); ?>
    <?php endif; ?>

    <?php echo CHtml::tag("div", array('field'=>'field_size'), $form->textFieldRow($model, 'field_size', array('class'=>'span4', 'hint'=>Yii::t('site', "Field size column in the database."), 'readonly'=>(!empty($model->id_user_field)?true:false)))); ?>
    <?php echo CHtml::tag("div", array('field'=>'field_size_min'), $form->textFieldRow($model, 'field_size_min', array('class'=>'span4', 'hint'=>Yii::t('site', "The minimum value of the field (form validator)."), 'readonly'=>(!empty($model->id_user_field)?true:false)))); ?>
    <?php echo CHtml::tag("div", array('field'=>'required'), $form->dropDownListRow($model, 'required', ProfileField::values ('required'), array('class'=>'span4', 'hint'=>Yii::t('site', 'Required field (form validator).')))); ?>
    <?php echo CHtml::tag("div", array('field'=>'match'), $form->textFieldRow($model, 'match', array('class'=>'span4', 'hint'=>Yii::t('site', "Regular expression (example: '/^[A-Za-z0-9\s,]+$/u').")))); ?>
    <?php echo CHtml::tag("div", array('field'=>'range'), $form->textFieldRow($model, 'range', array('class'=>'span4', 'hint'=>Yii::t('site', 'Predefined values (example: 1;2;3;4;5 or 1==One;2==Two;3==Three;4==Four;5==Five).')))); ?>
    <?php echo CHtml::tag("div", array('field'=>'error_message'), $form->textFieldRow($model, 'error_message', array('class'=>'span4', 'hint'=>Yii::t('site', 'Error message when you validate the form.')))); ?>
    <?php echo CHtml::tag("div", array('field'=>'default'), $form->textFieldRow($model, 'default', array('class'=>'span4', 'hint'=>Yii::t('site', 'The value of the default field (database).'), 'readonly'=>(!empty($model->id_user_field)?true:false)))); ?>
    <?php echo CHtml::tag("div", array('field'=>'position'), $form->textFieldRow($model, 'position', array('class'=>'span4', 'hint'=>Yii::t('site', 'Display order of fields.')))); ?>
    <?php echo CHtml::tag("div", array('field'=>'visible'), $form->dropDownListRow($model, 'visible', ProfileField::values ('visible'), array('class'=>'span4'))); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>