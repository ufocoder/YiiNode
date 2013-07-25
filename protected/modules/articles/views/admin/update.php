<?php
    /* @var $this ArticlesController */
    /* @var $model Article */

    $nodeId = Yii::app()->getNodeId();

    $this->breadcrumbs=array(
        Yii::t('site', 'Article list') => array('/admin/node/'.$nodeId),
        CHtml::encode($model->title) => array('view', 'id'=>$model->id_article),
        Yii::t('site', 'Update'),
    );

    $baseUrl = Yii::app()->baseUrl;
    Yii::app()->getClientScript()->registerScriptFile($baseUrl.'/js/admin.js');

    $this->actions = array(
        array('label'=>Yii::t('site', 'View article'), 'url'=>array('view', 'id'=>$model->id_article)),
        array('label'=>Yii::t('site', 'Delete article'), 'url'=>array('delete', 'id'=>$model->id_article),
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );

    $this->title = Yii::t("site", "Update company");

?>
<?php echo $this->renderPartial('_form', array('model'=>$model, 'users'=>$users)); ?>