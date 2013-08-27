<?php
    $baseUrl = Yii::app()->request->baseUrl;
?>
<?php $this->renderPartial('//layouts/__header'); ?>
<div class="container-narrow">

    <div class="masthead">
        <h2>YiiNode</h2>

        <?php $this->widget('zii.widgets.CMenu', array(
          'id'=>null,
          'items'=>array(
            array('label'=>Yii::t('site', 'Login'), 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
            array('label'=>Yii::t('site', 'Registration'), 'url'=>array('/user/registration'), 'visible'=>Yii::app()->user->isGuest),
            array('label'=>Yii::t('site', 'Account'), 'url'=>array('/user/profile'), 'visible'=>!Yii::app()->user->isGuest),
            array('label'=>Yii::t('site', 'Logout').' ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest)
          ),
          'htmlOptions' => array(
              'class' => 'nav nav-pills pull-right',
          )
        ));
      ?>
    </div>

  <hr>

<?php $this->renderPartial('//layouts/__menu'); ?>


  <?php

  $nodes = Yii::app()->getNodeChain();

  $breadcrumbs = array();
  foreach ($nodes as $node)
      $breadcrumbs[$node->title] = $node->path;

  if (!empty($breadcrumbs))
      $this->widget('zii.widgets.CBreadcrumbs', array(
          'homeLink' => false,
          'links' => array_merge($breadcrumbs, $this->breadcrumbs)
      ));
  else
      $this->widget('zii.widgets.CBreadcrumbs', array(
          'links' => $this->breadcrumbs
      ));

  ?>

  <div class="row-fluid">
  <?php if (!empty($this->title)): ?>
    <h1><?php echo $this->title; ?></h1>
  <?php endif; ?>

  <?php if (!empty($this->actions)):?>
    <div style="margin-bottom: 15px;">
        <?php $this->widget('zii.widgets.CMenu', array(
                'items' => $this->actions,
                'htmlOptions' => array(
                  'class' => 'nav nav-pills'
                )
            ));
        ?>
    </div>
  <?php endif; ?>

      <?php echo $content; ?>
  </div>

  <hr>

  <div class="footer">
    <p>&copy; <?php echo date("Y");?>, <?php echo Block::getValue('Footer: copyright', 'string', '{copyright}')?></p>
  </div>

</div>
<?php $this->renderPartial('//layouts/__footer'); ?>