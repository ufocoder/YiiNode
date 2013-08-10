<?php
    if ($nodeId = Yii::app()->getNodeId())
    {
        $node = Yii::app()->getNode();
        $this->breadcrumbs = array_merge(array(
            Yii::t("site", "Structure") => Yii::app()->createUrl('/admin/node/'),
            $node->title => Yii::app()->createUrl('/admin/node/'.$node->id_node),
        ), $this->breadcrumbs);

        $nodeActions = array(
            array('label' => Yii::t('site', 'Node content'), 'url' => Yii::app()->createUrl('/admin/node/'.$nodeId), 'icon' => 'file'),
            array('label' => Yii::t('site', 'Node settings'), 'url' => Yii::app()->createUrl('/admin/node/update', array('id'=>$nodeId)), 'icon' => 'pencil'),
            array('label' => Yii::t('site', 'Node move'), 'url' => Yii::app()->createUrl('/admin/node/move', array('id'=>$nodeId)), 'icon' => 'move'),
            array('label' => Yii::t('site', 'Node trash'), 'url' => Yii::app()->createUrl('/admin/node/delete', array('id'=>$nodeId)), 'icon' => 'trash',
                'htmlOptions' => array(
                    'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                    'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
                )
            ),
        );
        $nodeTitle = Yii::app()->getNodeAttribute('title');
    }
?>
<?php $this->beginContent('application.modules.admin.views.layouts.main'); ?>
                <div class="span3" id="left-menu">

<?php if (!empty($this->menu)): ?>
    <h3><?php echo Yii::t('site', 'Menu');?></h3>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=>'list',
        'items'=> $this->menu,
        'htmlOptions' => array(
            'class'=> 'well'
        )
    )); ?>
<?php else: ?>
<?php $this->renderPartial('application.modules.admin.views.layouts.widgets.nodes'); ?>
<?php endif; ?>

                </div>
                <div class="span9">
    <?php if (!empty($nodeTitle)):?>
        <h2>
            <?php echo $nodeTitle;?>
            <?php if (!empty($this->nodeButton))
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type' => 'primary',
                    'size' => 'small',
                    'encodeLabel' => false,
                    'toggle' => 'radio',
                    'buttons' => $this->nodeButton
                ));
            ?>
        </h2>
    <?php endif; ?>

    <?php
        if (!empty($nodeActions))
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'buttons' => $nodeActions,
                'htmlOptions' => array(
                    'style'=> 'margin: 0 0 10px 0;'
                )
            ));
    ?>

    <?php if (!empty($this->title)):?>
        <h3>
            <?php echo $this->title;?>
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
        if (!empty($this->actions))
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'buttons' => $this->actions,
                'htmlOptions' => array(
                    'style'=> 'margin: 0 0 10px 0;'
                )
            ));
    ?>

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
