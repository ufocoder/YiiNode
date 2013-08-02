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

    public function onCreate($event)
    {
        $node = $event->sender;

        $page = new Page('create');
        $page->id_node = $node->id_node;
        $page->title = $node->title;
        $page->save();
    }

}
