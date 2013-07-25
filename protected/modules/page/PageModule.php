<?php
/**
 * Page module
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class PageModule extends WebModule
{ 
    /**
     * Правила маршрутизации
     */
    public function route()
    {
        return array(
            '/' =>'page/default/index',
        );
    }
}
