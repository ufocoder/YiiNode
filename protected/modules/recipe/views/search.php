<?

    $nodeId = Yii::app()->getNodeID();
    
    $this->breadcrumbs = array(
        Yii::t('all', 'Search')=>array('index')
    );
    $this->pageTitle = Yii::t('all', 'Search result');

?>

<?php
    // Форма поиска:
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl('/search'),
        'id' => 'search-form',
        'method' => 'GET',
        'htmlOptions' => array(
            'class'=>'recipe-search-form'
        )
    ));
?>
    <?php echo CHtml::TextField('q', $term, array('class'=>'input', 'style'=>'width: 230px'))?>
    <span>&nbsp;</span>
    <?php echo CHtml::submitButton(Yii::t("site", "Search"), array('class'=>'button', 'name'=>null))?>
<?php    $this->endWidget(); ?>


<?php 
    // Результаты поиска
    if (!empty($recipes)):
        $i=0;
?>
    <div class="recipe-search-result message">
    <?php echo Yii::t('all', 'Search result: {total} records found', array(
        '{total}' => $count
    )); ?>
    </div>

    <div class="recipe-search-list">
<?php foreach($recipes as $recipe): ?>
    <a href="<?php echo Yii::app()->createUrl('recipe/default/view', array('id'=>$item->id_recipe)); ?>"><h3><?php echo $item->title;?></h3></a>
<?php endforeach; ?>
    </div>

<?php
    $this->renderPartial('//pager', array(
        'id' => 'recipe-pager',
        'pages' => $pages
    )); 
?>

<?php else:?>
    <div class="message" style="margin: 10px 0;">
        <?php echo Yii::t('all', 'Search result is empty');?>
    </div>
<?php  endif; ?>