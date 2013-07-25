
                    <h4><?php echo Yii::t('site', 'Site structure');?> <a href="/admin/node/create"><button class="btn btn-mini"><i class="icon-plus"></i><?php echo Yii::t('site', 'Node add');?></button></a> </h4>

<a href="<?php echo Yii::app()->createUrl('/admin/node/create');?>"></a>
<?php
    $model = Node::model();
    $nodes = $model->getCommandBuilder()
       ->createFindCommand($model->tableSchema, $model->dbCriteria)
       ->queryAll(array(
        'index' => 'id_node',
        'order' => 'id_node_parent ASC, position ASC'
    ));

    $nodeId = Yii::app()->getNodeId();
    
    if (!empty($nodes)){
        $nodes[0]['open'] = true;
        foreach($nodes as &$node){
            $node['url'] = Yii::app()->createUrl('/admin/node/'.$node['id_node']);
            $node['target'] = '_self';
            if ($node['id_node'] == $nodeId){
                $node['font'] = array(
                    "background-color" => "#f0f0f0",
                );
            }
        }
    }

    $zTree = $this->widget('ext.ztree.zTree',array(
        'treeNodeNameKey'=>'title',
        'treeNodeKey'=>'id_node',
        'treeNodeParentKey'=>'id_node_parent',
        'cssFile' => array('bootstrapStyle.css'),
        'data' => $nodes,
        'options'=>array(
            'view' => array(
                'fontCss' => 'js:function(treeId, node){ return node.font ? node.font : {}; }',
            )
        )
    ));
?>