<?php
/**
 * Админ-панель [с поддержкой Bootstrap]
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class AdminModule extends WebModule
{
    /**
     * Инициализация модуля [начальная настройка]
     */
    public function init()
    {
        parent::init();

        // Импорт моделей и компонентов на уровне модуля
        $this->setImport(array(
            'admin.components.*',
            'admin.models.*',
        ));

        // Загружаем Bootstrap
        $this->configure(array(
            'components'=>array(
                'bootstrap'=>array(
                    'class'=>'ext.booster.components.Bootstrap'
                )
            )
        ));
        $this->getComponent('bootstrap');

        // переопределяем ссылки для администратора
        Yii::app()->user->recoveryUrl = array('/admin/recovery');
        Yii::app()->user->loginUrl = array('/admin/login');
        Yii::app()->user->returnUrl = array('/admin');
        Yii::app()->user->returnLogoutUrl = array('/admin/login');
    }

}
