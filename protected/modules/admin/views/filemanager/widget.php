<?php
    /* @var $this FilemanagerController */
    $this->widget('ext.elfinder2.ElFinderWidget', array(
        'connectorRoute' => 'admin/filemanager/connector',
        'settings' => array(
            'editorCallback'=>'js:function(url) {
                var funcNum = window.location.search.replace(/^.*CKEditorFuncNum=(\d+).*$/, "$1");
                window.opener.CKEDITOR.tools.callFunction(funcNum, url);
                window.close();
            }',
        )
    ));
?>