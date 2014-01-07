<?php
/**
 * Redirect module
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class TranslationModule extends WebModule
{
    /**
     * Flag module is node type
     */
    public $nodeModule = true;

    /**
     * Module initialize
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Returns the description of this module.
     * @return string the description of this module.
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Returns the version of this module.
     *
     * @return string the version of this module.
     */
    public function getVersion()
    {
        return '1.0';
    }


    /**
     * Правила маршрутизации
     */
    public function route()
    {
        return array(
            '/*' => 'translation/default/index',
        );
    }

}
