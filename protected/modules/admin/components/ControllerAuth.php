<?php
/**
 * Компонент auth-контроллер
 * подключаем layout и фильтра для доступа
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class ControllerAuth extends Controller
{
    public $layout = '/layouts/form';

    /**
     * Список фильтров
     *
     * @return type
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

}