<?php
    /* @var $this ArticlesController */
    /* @var $model Article */

    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('default/view', array('id'=>$model->id_article, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('default/delete', array('id'=>$model->id_article, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update article");
    $this->breadcrumbs=array(
        Yii::t('site', 'Article #{id}', array('{id}'=>$model->id_article)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $baseUrl = Yii::app()->baseUrl;
    Yii::app()->getClientScript()->registerScriptFile($baseUrl.'/js/admin.js');

    $this->actions = array(
        array('label'=>Yii::t('site', 'View article'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete article'), 'url' => Yii::app()->createUrl('default/delete', array('id'=>$model->id_article, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<?php echo $this->renderPartial('/admin/_form', array('model'=>$model)); ?>