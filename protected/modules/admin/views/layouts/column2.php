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
<?php

    if ($nodeId = Yii::app()->getNodeId())
    {
        $node = Yii::app()->getNode();
        $this->breadcrumbs = array_merge(array(
            Yii::t("site", "Structure") => array('/admin/node'),
            $node->title => array('/admin/node/'.$node->id_node),
        ), $this->breadcrumbs);

        $nodeActions = array(
            array('label' => Yii::t('site', 'Node content'), 'url' => Yii::app()->createUrl('/admin/node/'.$nodeId), 'icon' => 'file'),
            array('label' => Yii::t('site', 'Node settings'), 'url' => Yii::app()->createUrl('/admin/node/update', array('id'=>$nodeId)), 'icon' => 'pencil'),
            array('label' => Yii::t('site', 'Node move'), 'url' => Yii::app()->createUrl('/admin/node/move', array('id'=>$nodeId)), 'icon' => 'move'),
            array('label' => Yii::t('site', 'Node trash'), 'url' => Yii::app()->createUrl('/admin/node/delete', array('id'=>$nodeId)), 'icon' => 'trash'),
        );
    }

    $nodeTitle = Yii::app()->getNodeAttribute('title');
?>

    <?php if (!empty($nodeTitle)):?>
        <h2><?php echo $nodeTitle;?></h2>
    <?php endif; ?>

<?php if (!empty($nodeActions)): ?>
    <div style="margin-bottom: 15px;">
        <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'toggle' => 'radio',
                'buttons' => $nodeActions
            ));
        ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($this->title)):?>
                <h3>
                    <?php echo $this->title?>
                    <?php if (!empty($this->titleButton))
                        $this->widget('bootstrap.widgets.TbButtonGroup', array(
                            'type' => 'primary',
                            'size' => 'small',
                            'encodeLabel' => false,
                            'toggle' => 'radio',
                            'buttons' => $this->titleButton
                        ));
                    ?>
                </h3>
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
