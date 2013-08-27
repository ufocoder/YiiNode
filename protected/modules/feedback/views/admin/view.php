<?php
    /* @var $this ContactController */
    /* @var $model Contact */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('/admin/feedback/default/update', array('id'=>$model->id_feedback));
    $deleteUrl = Yii::app()->createUrl('/admin/feedback/default/delete', array('id'=>$model->id_feedback));

    $this->title = Yii::t("site", "Update feedback");
    $this->breadcrumbs=array(
        Yii::t('site', 'Feedback') => array('/admin/feedback/default/index'),
        Yii::t('site', 'Feedback #{id}', array('{id}'=>$model->id_feedback))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update feedback'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete feedback'), 'url' => Yii::app()->createUrl('/admin/feedback/default/delete', array('id'=>$model->id_feedback)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Common information')?></legend>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>$model,
        'attributes'=>array(
            'person_name',
            'contact_phone',
            'contact_email',
            array(
                'name'=>'time_created',
                'value'=>$model->date_created,
            ),
            array(
                'name'=>'time_readed',
                'value'=>$model->date_readed
            )
        ),
    )); ?>
</fieldset>

<?php if (!empty($model->content)):?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Feedback content')?></legend>
    <div>
        <?php CHtml::encode($model->content);?>
    </div>
</fieldset>
<?php endif; ?>