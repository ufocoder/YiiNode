<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView' => '/item',
	'template'      => "{items}\n{pager}",
	'pagerCssClass' => 'paging',
	'pager'=>array(
		//'class' => 'PagerWidget',
	)
));
?>