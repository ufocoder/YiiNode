<?php
    $this->breadcrumbs = array(
        Yii::t('site', 'Catalog')=>array('/catalog'),
        Yii::t('catalog', 'Import schema')
    );

    $this->title = Yii::t('catalog', 'Import schema');
?>

<div class="alert alert-info">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong><?php echo Yii::t('site', 'Warning')?>:</strong>
  <ul>
    <li>чтобы указать какие разделы необходимо транслировать - перенесите раздел из структуры импорта в структуру категорий </li>
    <li>для совершения операция над перенесенным разделом нажмите правой кнопкой мыши, вызывав контекстное меню</li>
  </ul>
</div>

<style type="text/css">
<!--

#zTreeMenu {
    position:absolute;
    visibility:hidden;
    top:0;
    text-align: left;
    z-index: 10;
    font-size: 12px;
}

.nav-ztree {
    background: #fff;
    border: 1px solid #ccc;
    margin: 0;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 0 10px rgba(0,1,0,0.5);
       -moz-box-shadow: 0 0 10px rgba(0,1,0,0.5);
            box-shadow: 0 0 10px rgba(0,1,0,0.5);
}

.nav-ztree li:first-child{
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
       -moz-border-radius-topleft: 5px;
       -moz-border-radius-topright: 5px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
    border-top: 0;
}

.nav-ztree li{
    border-top: 1px dashed #ccc;
}

.nav-ztree a{
    padding: 4px 8px;
}

.nav-ztree ul:first-child a{
    margin-top: 0;
}

.nav-ztree   ul:last-child a{
    margin-bottom: 0;
}

.well.bg-right{
    overflow: hidden;
    background-image: url("../img/bg.right.png");
    background-repeat: repeat-y;
}
-->
</style>

<div id="zTreeMenu">
    <ul class="nav nav-ztree nav-stacked">
        <li id="zTreeMenu_deleteCurrent">
            <?php echo CHtml::link('<i class="icon-trash"></i>Удалить выбранную трансляцию', array('#'), array('confirm' => 'Вы уверены что хотите удалить все связи?'));?>
        </li>
    </ul>
</div>

<div class="row-fluid">
    <div class="span5">
        <h4>Структура импорта</h4>
        <div class="well bg-right">
<?php
    $zTreeImport = $this->widget('ext.ztree.zTree',array(
        'treeNodeNameKey'=>'title',
        'treeNodeKey'=>'id',
        'treeNodeParentKey'=>'id_parent',
        'cssFile' => array('bootstrapStyle.css'),
        'data'=>$data_import,
        'options'=>array(
            'edit' => array(
                'showRemoveBtn' => false,
                'showRenameBtn' => false,
                'enable' => true,
                'drag' => array(
                    'isCopy' => true,
                    'isMove' => false,
                    'prev' => false,
                    'next' => false,
                    'inner' => false,
                ),
                "removeTitle" => Yii::t('site', 'Delete'),
                "renameTitle" => Yii::t('site', 'Rename'),
            ),
            'callback' => array(
                'onDrop'  => 'js:onDrop',
                'beforeDrop'  => 'js:beforeDropImport'
            )
        )
    ));
?>
        </div>
    </div>
    <div class="span2"></div>
    <div class="span5">
        <h4>Структура категорий
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Удалить все трансляции',
            'type'=>'primary',
            'size'=>'mini',
            'url'=>array('/catalog/import/clear'),
            'htmlOptions'=>array('onclick'=>'return confirm("Вы уверены что хотите удалить все связи?")')
        )); ?>
        </h4>
        <div class="well bg-right">

<?php
    $zTreeCategory = $this->widget('ext.ztree.zTree',array(
        'treeNodeNameKey'=>'title',
        'treeNodeKey'=>'id',
        'treeNodeParentKey'=>'id_parent',
        'cssFile' => array('bootstrapStyle.css'),
        'data'=>$data_category,
        'options'=>array(
            'edit' => array(
                'showRemoveBtn' => false,
                'showRenameBtn' => false,
                'enable' => true,
            ),
            'view' => array(
                'nameIsHTML' => true,
                'fontCss' => 'js:getFont',
            ),
            'callback' => array(
                'beforeDrag'  => 'js:beforeDrag',
                'beforeDrop'  => 'js:beforeDropCategory',
                'onRightClick' => 'js:OnRightClick'
            )
        )
    ));
?>
        </div>
    </div>
</div>
<?php
    $script = "
    var zTreeImport = $.fn.zTree.getZTreeObj('".$zTreeImport->id."'),
        zTreeCategory =$.fn.zTree.getZTreeObj('".$zTreeCategory->id."'),
        schemaUrl = '".Yii::app()->createUrl('catalog/import/schema')."',
        clearItemUrl = '".Yii::app()->createUrl('catalog/import/clearItem')."',
        tokenName = '".Yii::app()->request->csrfTokenName."',
        tokenValue = '".Yii::app()->request->csrfToken."'
        zTreeMenu  = $('#zTreeMenu');


    var pathList = ".CJSON::encode($path).";

    $('#zTreeMenu_deleteCurrent').click(function(){

        var dataAjax = {},
            nodes = zTreeCategory.getSelectedNodes();
            parent = nodes[0].getParentNode();

        if (nodes[0].import != true)
            return;

        dataAjax[tokenName] = tokenValue;
        dataAjax['id_tree'] = nodes[0].itemId;
        dataAjax['id_category'] = parent.itemId;

        $.ajax(clearItemUrl, {
            dataType: 'JSON',
            type: 'POST',
            data: dataAjax,
        }).done(function(data){
            zTreeCategory.removeNode(nodes[0]);
        });

        return false;
    });

    function getFont(treeId, node) {
        return node.font ? node.font : {};
    }

    function changeBeforeDropImport(treeNodes, parentName){
        var node = null,
            prefix = '';
        parentName = parentName || '';
        for ( i in treeNodes){
            $('#'+treeNodes[i].tId+'_a').attr('title', parentName + '/' +treeNodes[i].name);

            treeNodes[i].name = pathList[treeNodes[i].itemId];
            treeNodes[i].drag = true;
            treeNodes[i].drop = true;
            treeNodes[i].dropRoot = true;
            treeNodes[i].import = true;
            if ('children' in treeNodes[i])
                changeBeforeDropImport(treeNodes[i].children, treeNodes[i].name);
        }
    }

    function beforeDrag(treeId, treeNodes){
        var flag = true;
        for(i in treeNodes){
            console.log(treeNodes[i])
            if (treeNodes[i].import != true){
                flag = false;
                break;
            }
        }
        console.log(flag);
        return flag;
    };

    function changeOnDrop(treeNodes, parentName){
        parentName = parentName || '';
        for ( i in treeNodes){
            $('#'+treeNodes[i].tId+'_a').css({background:'#faffc4'});
            treeNodes[i].drag = true;
            treeNodes[i].drop = true;
            treeNodes[i].dropInner = false;
            if ('children' in treeNodes[i])
                changeOnDrop(treeNodes[i].children, treeNodes[i].name);
        }
    }

    function collectListId(treeNodes, listId){
        var i;
        console.log(treeNodes);
        for ( i in treeNodes){
            listId.push(treeNodes[i].itemId);

            if ('children' in treeNodes[i])
                collectListId(treeNodes[i].children, listId);
        }
    }

    function beforeDropImport(treeId, treeNodes, targetNode, moveType){
        if (targetNode.import == true)
            return;

        var dataAjax = {},
            listId = new Array;

        collectListId(treeNodes, listId);

        dataAjax[tokenName] = tokenValue;
        dataAjax['Schema'] = {
            ImportNodes: listId,
            CategoryId: targetNode.id
        }

        $.ajax(schemaUrl, {
            dataType: 'JSON',
            type: 'POST',
            data: dataAjax,
        }).done(function(data){
            //console.log(data);
        });

        changeBeforeDropImport(treeNodes);
        return true;
    }

    function beforeDropCategory(treeId, treeNodes, targetNode, moveType) {

        if (targetNode.import == true)
            return;

        var dataAjax = {},
            listId = new Array;

        collectListId(treeNodes, listId);

        dataAjax[tokenName] = tokenValue;
        dataAjax['Schema'] = {
            ImportNodes: listId,
            CategoryId: targetNode.id
        }

        $.ajax(schemaUrl, {
            dataType: 'JSON',
            type: 'POST',
            data: dataAjax,
        }).done(function(data){
            console.log(data);
        });

        //changeBeforeDrop(treeNodes);
        return true;
    }

    function onDrop(event, treeId, treeNodes, targetNode, moveType) {
        changeOnDrop(treeNodes);
        return targetNode ? targetNode.drop !== false : true;

    }


    function OnRightClick(event, treeId, treeNode) {
        if (!treeNode && event.target.tagName.toLowerCase() != 'button' && $(event.target).parents('a').length == 0 && treeNode.import) {
            zTreeCategory.cancelSelectedNode();
            showzTreeMenu('root', event.pageX+10, event.pageY-10);
        } else if (treeNode && !treeNode.noR && treeNode.import) {
            zTreeCategory.selectNode(treeNode);
            showzTreeMenu('node', event.pageX+10, event.pageY-10);
        }
    }

    function showzTreeMenu(type, x, y) {
        $('#zTreeMenu ul').show();
        if (type=='root') {
            $('#m_del').hide();
            $('#m_check').hide();
            $('#m_unCheck').hide();
        } else {
            $('#m_del').show();
            $('#m_check').show();
            $('#m_unCheck').show();
        }
        zTreeMenu.css({'top':y+'px', 'left':x+'px', 'visibility':'visible'});

        $('body').bind('mousedown', onBodyMouseDown);
    }
    function hidezTreeMenu() {
        if (zTreeMenu) zTreeMenu.css({'visibility': 'hidden'});
        $('body').unbind('mousedown', onBodyMouseDown);
    }

    function onBodyMouseDown(event){
        if (!(event.target.id == 'zTreeMenu' || $(event.target).parents('#zTreeMenu').length>0)) {
            zTreeMenu.css({'visibility' : 'hidden'});
        }
    }
";

    Yii::app()->getClientScript()->registerCoreScript('jquery');
    Yii::app()->clientScript->registerScript("widgets-zTree", $script, CClientScript::POS_READY);
?>