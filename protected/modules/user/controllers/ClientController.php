<?php

class ClientController extends Controller
{
    public $defaultAction = 'list';

    public function actions()
    {
        Yii::import('application.modules.order.models.*');

        return array(
            'list' => 'application.modules.order.actions.ClientListAction',
            'view' => 'application.modules.order.actions.ClientViewAction',
        );
    }

    public function accessRules()
    {
        return array
        (
            array('allow',
                'roles'=>array(WebUser::ROLE_MANAGER),
            ),
            array('deny')
        );
    }

}