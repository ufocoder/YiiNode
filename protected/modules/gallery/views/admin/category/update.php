<?php
    /* @var $this DefaultController */
    /* @var $model GalleryCategory */

    $nodeId = Yii::app()->getNodeId();
    $listUrl = Yii::app()->createUrl('default/index', array('id_category'=>$model->id_gallery_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $viewUrl = Yii::app()->createUrl('category/view', array('id'=>$model->id_gallery_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('category/delete', array('id'=>$model->id_gallery_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    if (empty($model->title))
        $categoryTitle = Yii::t('site', 'Gallery category #{id}', array('{id}'=>$model->id_gallery_category));
    else
        $categoryTitle = $model->title;

    $this->title = Yii::t("site", "Update category");
    $this->breadcrumbs=array(
        $categoryTitle => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Image list'), 'url' => $listUrl, 'icon'=>'list'),
        array('label'=>Yii::t('site', 'View category'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete category'), 'url' => Yii::app()->createUrl('category/delete', array('id'=>$model->id_gallery_category, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<?php echo $this->renderPartial('/admin/category/_form', array('model'=>$model)); ?>