<?php
    $nodeId = Yii::app()->getNodeId();

    $column = Yii::app()->getNodeSetting($nodeId, 'column', GallerySetting::values('column', 'default'));
    $width = Yii::app()->getNodeSetting($nodeId, 'width', GallerySetting::values('width', 'default'));
    $height = Yii::app()->getNodeSetting($nodeId, 'height', GallerySetting::values('height', 'default'));
    $resize = Yii::app()->getNodeSetting($nodeId, 'resize');
?>

<?php if (!empty($categories)): ?>
<div>
    <?php foreach($categories as $category):
        $url = Yii::app()->createUrl('/gallery/default/category', array('id'=>$category->id_gallery_category, 'nodeId'=>$category->id_node));
    ?>
        <div>
            <a href="<?php echo $url?>">
                <?php echo $category->title;?>
            </a>
        </div>
    <?php endforeach;?>
</div>
<?php endif; ?>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '/item',
    'template'      => "{items}\n{pager}",
    'viewData' => array(
        'column' => $column,
        'height' => $height,
        'width' => $width,
        'resize' => $resize
    ),
    'pagerCssClass' => 'paging',
    'pager'=>array(
        //'class' => 'PagerWidget',
    )
));
?>