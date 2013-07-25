<?php
    $baseUrl = Yii::app()->request->baseUrl;
?>
<?php $this->renderPartial('//layouts/__header'); ?>

  <div id="content">
    <div class="container">
<?php if (!empty($this->breadcrumbs)): ?>
      <section id="breadcrumbs">
        <div class="container">
<?php $this->widget('zii.widgets.CBreadcrumbs', array(
  'links'=>$this->breadcrumbs,
)); ?>
        </div>
      </section>
<?php endif; ?>
<?php if (!empty($this->title)): ?>
    <h1><?php echo $this->title;?></h1>
<?php endif; ?>

    <?php echo $content; ?>
    </div>
  </div>
<?php $this->renderPartial('//layouts/__footer'); ?>
