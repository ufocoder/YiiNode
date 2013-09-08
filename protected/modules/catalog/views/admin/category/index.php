<?php

    $this->title = Yii::t('site', 'Category list');
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('category/create', array('nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId())), 'icon'=>'white plus')
    );
    $this->breadcrumbs = array(
        Yii::t('site', 'Category list')=>array('category/index', array('nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId())),
        Yii::t('site', 'Manage')
    );

    $modelName = "CatalogCategory";

    CatalogCategory::model()->updatePosition();

    $roots = CatalogCategory::model()->roots()->position()->findAll();
    $items = array();
    foreach ($roots as $root) {
        $items[] = array(
            'id' =>   $root->id_category,
            'text' => $root->title,
            'children' => $root->treechild($root->id_category)
        );
    }
?>

<div style="width: 400px">
    <?php $this->widget('CTreeView', array('data' => $items)); ?>
</div>
<? /*
<style>
<!--
    #createNode:hover { text-decoration:none; }
    #nodeTitle,
    #nodeImage,
    #nodeEnabled    { text-align: center; }
-->
</style>
<div class="row-fluid">
    <div class="span7">
        <a href="#" id="createNode" onclick="return false;">+ добавить раздел</a><br />

<?php
    $zTree = $this->widget('ext.ztree.zTree',array(
        'treeNodeNameKey'=>'title',
        'treeNodeKey'=>'id',
        'treeNodeParentKey'=>'id_parent',
        'cssFile' => array('bootstrapStyle.css'),
        'data'=>$data,
        'options'=>array(
            'edit' => array(
                'enable' => true,
                "removeTitle" => Yii::t('site', 'Delete'),
                "renameTitle" => Yii::t('site', 'Rename'),
            ),
            'callback' => array(
                'onClick'       => 'js:onNodeClick',
                'onRemove'      => 'js:onNodeRemove',
                'beforeRemove'  => 'js:beforeNodeRemove',
                'beforeRename'  => 'js:beforeNodeRename',
                'beforeDrop'    => 'js:beforeNodeDrop',
            ),
            'view' => array(
                'selectedMulti'     => false,
                'addHoverDom'       => 'js:addNodeHoverDom',
                'removeHoverDom'    => 'js:removeNodeHoverDom',

            )
        )
    ));
?>
    </div>

    <div class="span5 well" id="widgetCategory">
        <div id="nodeTitle"><i>Раздел не выбран</i></div>
        <div id="nodeImage"></div>
        <div id="nodeEnabled"></div>
        <div id="nodeEdit"></div>
    </div>
</div>
*/ ?>
<?php
/*
    $script = '
    var zTree = $.fn.zTree.getZTreeObj("'.$zTree->id.'"),
        currentNode = null,
        createUrl = "'.Yii::app()->createUrl('catalog/category/create').'",
        updateUrl = "'.Yii::app()->createUrl('catalog/category/update').'&id=",
        deleteUrl = "'.Yii::app()->createUrl('catalog/category/delete').'&id=",
        pathImage = "'.CatalogCategory::model()->getThumbPath('image','thumb').'",
        createdId = '.$createId.',
        createdName = "новый узел",
        newCount = 1;

    var tokenName = "'.Yii::app()->request->csrfTokenName.'",
        tokenValue = "'.Yii::app()->request->csrfToken.'";

    $("#createNode").bind("click", createNode);

    function createNode(e){
        var nodes = zTree.getSelectedNodes(),
            treeNode = nodes[0],
            tree_id,
            dataAjax = {};

        if (!treeNode)
            tree_id = 0;
        else
            tree_id = treeNode.id;

        dataAjax[tokenName] = tokenValue;
        dataAjax["Category"] = {
            "title": createdName,
            "tree_position": "inner",
            "tree_id": tree_id,
        };

        $.ajax(createUrl, {
            dataType: "JSON",
            type: "POST",
            data: dataAjax,
        }).done(function(data){
            if (!!data.success){
                if (treeNode) {
                    treeNode = zTree.addNodes(treeNode, {id:(createdId + newCount), pId:treeNode.id, name: createdName + (newCount++)});
                } else {
                    treeNode = zTree.addNodes(null, {id:(createdId + newCount), pId:0, name: createdName + (newCount++)});
                }
                zTree.editName(treeNode[0]);
            }
        });
    }

    // good
    function onNodeClick(event, treeId, treeNode){
        currentNode = treeNode;
        viewCurrentNode(treeNode.id);
    };

    function beforeNodeRemove(treeId, treeNode){
        if (confirm("Вы действительно хотите удалить \'" + treeNode.name + "\'?")){
            var nodes = zTree.getSelectedNodes();
            zTree.hideNodes(nodes);
            return true;
        }
    }

    function onNodeRemove(event, treeId, treeNode) {

        var dataAjax = {};
        dataAjax[tokenName] = tokenValue;

        $.ajax({
            url: deleteUrl + treeNode.id,
            type: "POST",
            dataType: "JSON",
            data: dataAjax
        }).done(function(data){
            if (!!data.success){
                zTree.removeNode(treeNode, false);
                currentNode = null;
            }
        });
    }

    function addNodeHoverDom(treeId, treeNode) {

        var sObj = $("#" + treeNode.tId + "_span");
        if (treeNode.editNameFlag || $("#update_"+treeNode.id).length>0) return;

        var addStr = "<a href=\'" + (updateUrl + treeNode.id) + "\' id=\'update_" + treeNode.id + "\'><i class=\'icon-pencil\' title=\'Редактировать\'></i></a>";
        sObj.after(addStr);

    };

    function removeNodeHoverDom(treeId, treeNode) {
        $("#update_"+treeNode.id).unbind().remove();
    };

    function viewCurrentNode(id){
        if (!!currentNode){
            $("#nodeTitle").html(currentNode.name);
            if (!!currentNode.image)
                $("#nodeImage").html("<img src=\""+pathImage+currentNode.image+"\">");
            else
                $("#nodeImage").html("");
            if (currentNode.enabled == 1)
                $("#nodeEnabled").html("статус: активно");
            else
                $("#nodeEnabled").html("статус: не активно");
            $("#nodeEdit").html("<center><a href=\"'.Yii::app()->createUrl('/catalog/category/update').'&id=" + currentNode.id + "\">редактировать</a></center>");
        }else{
            $("#nodeTitle").html("Раздел не выбран");
            $("#nodeImage").html("");
            $("#nodeEnabled").html("");
            $("#nodeEdit").html("");
        }
    }

    function beforeNodeDrop(treeId, treeNodes, targetNode, moveType) {

        var dataAjax = {};
        dataAjax[tokenName] = tokenValue;
        dataAjax["Category"] = {
            "tree_position": moveType,
            "tree_id": targetNode.id,
        }

        $.ajax(updateUrl + treeNodes[0].id, {
            dataType: "JSON",
            type: "POST",
            data: dataAjax,
        }).done(function(data){

        });

        return true;
    }

    function beforeNodeRename(treeId, treeNode, newName) {

        var dataAjax = {};
        dataAjax[tokenName] = tokenValue;
        dataAjax["Category"] = {
            "title": newName,
        }

        $.ajax({
            url: updateUrl + treeNode.id,
            type: "POST",
            dataType: "JSON",
            data: dataAjax
        }).done(function(data){
             if (!!data.success){
              //  zTree.renameNode(treeNode, false);
            }
        });
    }
';

    Yii::app()->getClientScript()->registerCoreScript('jquery');
    Yii::app()->clientScript->registerScript("widget-zTree-".$zTree->id, $script, CClientScript::POS_READY);
*/
 ?>