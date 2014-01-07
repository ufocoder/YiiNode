<?php
    /* @var $this ContactController */
    /* @var $model Contact */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('default/update', array('id'=>$model->id_contact, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('default/delete', array('id'=>$model->id_contact, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update contact");
    $this->breadcrumbs=array(
        Yii::t('site', 'Contract #{id}', array('{id}'=>$model->id_contact))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update contact'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete contact'), 'url' => Yii::app()->createUrl('default/delete', array('id'=>$model->id_contact, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );

    $image = $model->image;
    $thumb = Yii::app()->image->thumbSrcOf($image, array('resize' => array('width' => 350)));
?>

<h4><?php echo Yii::t('site', 'Page content')?></h4>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'title',
        array(
            'name'=>'time_created',
            'value'=> $model->date_created,
        ),
        array(
            'name'=>'time_updated',
            'value'=> $model->date_updated,
        ),
        array(
            'name'  => 'image',
            'type' => 'raw',
            'value' => !empty($image)?(CHtml::link(CHtml::image($thumb), $image)):null,
        ),
        'timework',
        'email',
        'phone',
        'icq',
        'skype',
        array(
            'name'  => 'enabled',
            'value' => !empty($model->enabled)?Yii::t('site', 'Yes'):Yii::t('site', 'No')
        )
    ),
)); ?>


<h4><?php echo Yii::t('site', 'Address and coordinates')?></h4>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'addr',
        )
    ));
?>
<?php
    if (!empty($model->map_lat) && !empty($model->map_long))
        $this->widget('ext.maps.widgets.YandexMapPoint', array(
            'containerId' => 'ymap',
            'htmlOptions'=>array(
                'style'=> 'width:100%; height: 300px; margin: 10px 0;'
            ),
            'mapPoint' => array($model->map_lat, $model->map_long)
        ));
?>

<?php if (!empty($model->content)): ?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Content')?></legend>
    <div><?php echo $model->content;?></div>
</fieldset>
<?php endif; ?>