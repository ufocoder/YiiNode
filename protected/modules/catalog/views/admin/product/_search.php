<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'product-search',
        'type'=>'inline',
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'get',
        'htmlOptions'=>array(
            'class'=>'well'
        ),
    ));


    $categories = Yii::app()->db->createCommand()
        ->select("id, CONCAT(REPEAT('—', level), IF(level > 0,' ',''), `title`) as `title`")
        ->from(modules\catalog\models\Category::model()->tableName())
        ->queryAll();


    $data_category  = CHtml::listData($categories, 'id', 'title');
?>

<?php echo $form->textFieldRow($model, 'title', array('class'=>'input-medium', 'prepend'=>'<i class="icon-search"></i>')); ?>
<?php echo $form->dropDownList($model, 'id_category', $data_category, array('class'=>'span6', 'empty'=> Yii::t('catalog', 'Without category'))); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Yii::t('site', 'Search'))); ?>

<?php $this->endWidget(); ?>