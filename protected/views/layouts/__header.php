<?php
    $baseUrl = Yii::app()->request->baseUrl;
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="ru" />
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/css/bootstrap.min.css" />
<link rel="shortcut icon" href="favicon.ico" />
<script src="<?php echo $baseUrl; ?>/js/bootstrap.js"></script>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
  <div class="topline"></div>
  <header>
    <div class="container">
        <div class="topmenu-account">
<?php $this->widget('zii.widgets.CMenu',array(
    'id'=>null,
    'items'=>array(
      array('label'=>Yii::t('site', 'Login'), 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
      array('label'=>Yii::t('site', 'Registration'), 'url'=>array('/user/registration'), 'visible'=>Yii::app()->user->isGuest),
      array('label'=>Yii::t('site', 'Account'), 'url'=>array('/user/profile'), 'visible'=>!Yii::app()->user->isGuest),
      array('label'=>Yii::t('site', 'Logout').' ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest)
    ),
    'htmlOptions' => array(
        'class' => 'chain',
    )
  )); 
?>
      </div>
    </div>
  </header>