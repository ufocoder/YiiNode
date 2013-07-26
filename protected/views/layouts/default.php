<?php
    $baseUrl = Yii::app()->request->baseUrl;
?>
<?php $this->renderPartial('//layouts/__header'); ?>
<div class="container-narrow">

    <div class="masthead">
<?php $this->widget('zii.widgets.CMenu',array(
          'id'=>null,
          'items'=>array(
            array('label'=>Yii::t('site', 'Administration'), 'url'=>array('/admin')),
          ),
          'htmlOptions' => array(
              'class' => 'nav nav-pills pull-left',
          )
        ));
      ?>

      <?php $this->widget('zii.widgets.CMenu',array(
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

  <div class="header">
    <h1>YiiNode</h1>
    <p class="lead">Yii framework based CMS with tree structure.</p>
    <ul class="header-links">
      <li><a href="http://github.com/xifrin/YiiNode">GitHub Project</a></li>
      <li>Version <?php echo Yii::app()->params['version'];?></li>
    <ul>
  </div>

  <hr>

  <div class="row-fluid">
<?php echo $content; ?>
  </div>

  <hr>

  <div class="footer">
    <p>&copy; <?php echo date("Y");?></p>
  </div>

</div>
<?php $this->renderPartial('//layouts/__footer'); ?>