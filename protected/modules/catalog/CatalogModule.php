<?php
/**
 * Catalog module
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class CatalogModule extends WebModule
{
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
     * The default implementation returns '1.0'.
     * You may override this method to customize the version of this module.
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
            '/captcha/*'    => 'catalog/default/captcha',
            '/<id:\d+>'     => 'contact/default/view',
            '/'             => 'contact/default/index'
        );
    }

}