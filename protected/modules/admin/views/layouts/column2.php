<?php $this->beginContent('application.modules.admin.views.layouts.main'); ?>

                <div class="span3" id="left-menu">
                
    <?php if (!empty($this->menu)) :?>
                    <div class="page-header">
                        <h4><?php echo Yii::t('site', 'Menu');?></h4>
                    </div>
                    <?php $this->widget('bootstrap.widgets.TbMenu', array(
                        'type'=>'pills',
                        'stacked'=>true,
                        'items'=>$this->menu
                    )); ?>
    <?php endif; ?>

<?php
    $this->renderPartial('application.modules.admin.views.layouts.widgets.nodes');
?>
                </div>

                <div class="span9">
    <?php if (!empty($this->title)):?>
                <h2><?php echo $this->title?></h2>
    <?php endif; ?>

    <?php if (!empty($this->actions)):?>
    <div style="margin-bottom: 15px;">
        <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'toggle' => 'radio',
                'buttons' => $this->actions
            )); 
        ?>
    </div>
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
    <?php echo $content; ?>
            </div>

<?php $this->endContent(); ?>
