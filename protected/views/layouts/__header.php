<?php
    $baseUrl = Yii::app()->request->baseUrl;
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Le styles -->
    <link href="<?php echo $baseUrl; ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $baseUrl; ?>/css/main.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo $baseUrl; ?>/js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>