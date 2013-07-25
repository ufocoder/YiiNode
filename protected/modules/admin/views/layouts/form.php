<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title><?php echo Yii::t('site', 'Control panel'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
    <!--
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
    }
    .wrapper-form {
        max-width: 310px;
        padding: 19px 29px 29px;
        margin: 50px auto 10px auto;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
    }
    .wrapper-form .wrapper-form-heading,
    .wrapper-form .checkbox {
        margin-bottom: 10px;
    }
    .wrapper-form .text,
    .wrapper-form .password {
        font-size: 15px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
        display: table;
    }
    .captcha,
    .captcha-input {
        height: 50px;
    }
    .captcha {
        border: 1px solid #CCC;
        margin-bottom: 14px;
        -webkit-border-radius: 3px;
           -moz-border-radius: 3px;
                border-radius: 3px;
    }
    .wrapper-form .captcha-input {
        float: right;
        width: 170px;
        height: 42px;
        font-size: 15px;
        text-align: center;
    }
    .wrapper-form .alert-error ul {
        margin: 0;
        list-style: none;
    }
    .powered {
        text-align: center;
        font-size: 11px;
    }
    .powered a {
        color: #999;
    }
    .powered a:hover {
        color: #777;
    }
    -->
    </style>
    </head>
  <body>
    <div class="container">
        <div class="wrapper-form">
<?php if (!empty($this->title)): ?>
        <h2 class="form-signin-heading"><?php echo $this->title; ?></h2>
<?php endif; ?>
<?php 
    $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true,
        'fade'=>true,
        'closeText'=>'&times;',
        'alerts'=>array(
            'success'=>array(
                'block'=>true, 
                'fade'=>true, 
                'closeText'=>'&times;'
            ),
            'info'=>array(
                'block'=>true, 
                'fade'=>true, 
                'closeText'=>'&times;'
            ),
            'warning'=>array(
                'block'=>true, 
                'fade'=>true, 
                'closeText'=>'&times;'
            ),
            'error'=>array(
                'block'=>true, 
                'fade'=>true, 
                'closeText'=>'&times;'
            ),
        ),
    )); 
?>
<?php echo $content?>
        </div>
        <div class="powered">
            <a href="http://www.yiiframework.com/">
                <?php echo Yii::t("site", "Powered by Yii framework");?>
            </a>
        </div>
    </div>
  </body>
</html>