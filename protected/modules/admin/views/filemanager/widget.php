<?php
    $this->layout = null;

    $this->widget('ext.elfinder.ElFinderWidget', array(
        'connectorRoute' => 'admin/filemanager/connector',
    ));
?>