<?
    $this->breadcrumbs = array(
        Yii::t('site', 'Wellcome')
    );
?>

<?php
    if (Yii::app()->hasModule('feedback')):
        $path = 'application.modules.feedback';
        $count = 10;
        Yii::import($path.".models.Feedback");
        $model = Feedback::model();
?>
<div>
    <h3><?php echo Yii::t('site', 'Last {limit} feedback request', array('{limit}'=>$count));?></h3>
</div>
<?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped condensed',
        'dataProvider'=>$model->search(),
        'template'=>"{items}",
        'rowCssClassExpression' => 'empty($data->time_readed)?"info":null',
        'columns' => array(
            'person_name',
            array(
                'header' => Yii::t('site', 'Node'),
                'name' => 'Node.title',
            ),
            array(
                'name' => 'contact_email',
                'htmlOptions' => array(
                    'style' => 'width: 170px'
                )
            ),
            array(
                'name' => 'time_created',
                'value' => '$data->date_created',
                'htmlOptions' => array(
                    'style' => 'width: 150px'
                )
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'htmlOptions'=>array('style'=>'width: 50px'),
                'template'=>'{view} {delete}'
            ),
        ),
    ));
?>
<?php endif; ?>