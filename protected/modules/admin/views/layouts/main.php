<?php
    if (!empty($this->pageTitle)){
        if (is_array($this->pageTitle))
            $pageTitle = implode(" / ", $this->pageTitle);
        else
            $pageTitle = $this->pageTitle;
    }else
        $pageTitle = Yii::t('site', 'Control panel');

?><!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
<?php
    $cs = Yii::app()->getClientScript();
    $cs->registerCss('bootstrap-fix', '    body {
            padding-top: 60px;
            padding-bottom: 40px;
        }

        label{
            font-size: 13px;
        }

        table{
            font-size: 90%;
        }

        #wrapper{
            min-width: 1000px;
            width: 82%;
            margin: 0 auto;
        }

        .wrapper-container .container{
            min-width: 1000px;
            width: 82%;
        }

        .help-block{
            font-size: 12px;
        }

        .breadcrumbs {
            margin: 10px 0 !important;
        }
    ');

?>
  <body>
    <div id="wrapper">
    <?php $this->widget('bootstrap.widgets.TbNavbar', array(
        'brand'=> "Yii node",
        'htmlOptions'=>array(
            'class' => 'wrapper-container',
        ),
        'brandUrl'=> Yii::app()->request->getHostInfo(),
        'collapse'=>true,
        'items'=>array(
            array(
                'class'=>'bootstrap.widgets.TbMenu',
                'items'=>array(
                    array('label'=> Yii::t('site', 'Content'), 'url'=> '#', 'items'=>array(
                        array('label' => Yii::t('site', 'Structure'), 'url'=>Yii::app()->createUrl('/admin/node'), 'icon'=>'th'),
                        array('label' => Yii::t('site', 'Menu'), 'url'=>Yii::app()->createUrl('/admin/menu/list'), 'icon'=>'list-alt'),
                    )),
                    array('label'=> Yii::t('site', 'Template'), 'url'=>'#', 'items' => array(
                        array('label'=> Yii::t('site', 'Info blocks'), 'url' => Yii::app()->createUrl('/admin/block/'), 'icon'=>'list'),
                        array('label'=> Yii::t('site', 'Slider'), 'url' => Yii::app()->createUrl('/admin/slider/admin/index'), 'icon'=>'play'),
                    )),
                    array('label'=> Yii::t('site', 'Services'), 'url' => '#', 'items' => array(
                        array('label'=> Yii::t('site', 'Feedback'), 'url' => Yii::app()->createUrl('/admin/feedback/'), 'icon'=>'envelope'),
                        array('label'=> Yii::t('site', 'Filemanager'), 'url' => Yii::app()->createUrl('/admin/filemanager/'), 'icon'=>'file'),
                    )),
                    array('label'=> Yii::t('site', 'Settings'), 'url' => Yii::app()->createUrl('/admin/settings/')),
                    array('label'=> Yii::t('site', 'Users'), 'url' => '#', 'items'=> array(
                        array('label'=> Yii::t('site', 'Manage users'), 'url' => Yii::app()->createUrl('/admin/user/admin'), 'icon'=>'user'),
                        array('label'=> Yii::t('site', 'Manage profile fields'), 'url' => Yii::app()->createUrl('/admin/user/field'), 'icon'=>'list'),
                    )),
                ),
            ),
            array(
                'class'=>'bootstrap.widgets.TbMenu',
                'htmlOptions'=>array('class'=>'pull-right'),
                'items'=>array(
                    array('label'=> Yii::app()->user->getLogin(), 'type'=>'inverse', 'url'=>'#', 'items'=>array(
                        array('label' => Yii::t('site', 'Change password'), 'url'=>Yii::app()->createUrl('/admin/profile/changepassword'), 'icon'=>'lock'),
                        array('label' => Yii::t('site', 'Account settings'), 'url'=>Yii::app()->createUrl('/admin/profile/update'), 'icon'=>'user'),
                        '---',
                        array('label' => Yii::t('site', 'Logout'), 'url' => Yii::app()->createUrl('/admin/logout'), 'icon'=>'off'),
                    )),
                ),
            ),
        )
        ));
    ?>

        <div class="row-fluid">
    <?php if (!empty($this->breadcrumbs)):?>
            <div class="row-fluid">
    <?php
        $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
            'links' => $this->breadcrumbs,
            'homeLink' => CHtml::link(Yii::t('site', 'Control panel'), Yii::app()->createUrl('/admin'))
        ));
    ?>
            </div>
    <?php endif; ?>

            <div class="row-fluid">
<?php echo $content; ?>
            </div>
        </div>
        <hr>
    </div>
  </body>
</html>