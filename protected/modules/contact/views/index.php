<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '/item',
    'template'      => "{items}\n{pager}",
    'htmlOptions' => array(
        'class' => 'contact-list'
    )
)); ?>

<?php if ($feedback): ?>
<?php echo $this->renderPartial('/_feedback', array('model'=>$feedback)); ?>
<?php endif; ?>