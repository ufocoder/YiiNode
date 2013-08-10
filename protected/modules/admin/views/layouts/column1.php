<?php
    if ($nodeId = Yii::app()->getNodeId())
    {
        $node = Yii::app()->getNode();
        $this->breadcrumbs = array_merge(array(
            Yii::t("site", "Structure") => array('/admin/node'),
            $node->title => array('/admin/node/'.$node->id_node),
        ), $this->breadcrumbs);

    }
?>
<?php $this->beginContent('application.modules.admin.views.layouts.main'); ?>

                <div class="span12">
    <?php if (!empty($this->title)):?>
                <h2>
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
                </h2>
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
