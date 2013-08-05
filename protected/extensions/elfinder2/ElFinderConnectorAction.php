<?php

class ElFinderConnectorAction extends CAction
{
    public $options = array();

    public function run()
    {
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'/php/elFinderConnector.class.php';
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'/php/elFinder.class.php';
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'/php/elFinderVolumeDriver.class.php';
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'/php/elFinderVolumeLocalFileSystem.class.php';

        $connector = new elFinderConnector(new elFinder($this->options));
        $connector->run();
    }
}
