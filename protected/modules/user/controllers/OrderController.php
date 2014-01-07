<?php

class OrderController extends Controller
{
    public $defaultAction = 'list';

    public function actions()
    {
        Yii::import('application.modules.order.models.*');

        return array(
            'list' => 'application.modules.order.actions.ProfileListAction',
            'view' => 'application.modules.order.actions.ProfileViewAction',
        );
    }

    public function accessRules()
    {
        return array
        (
            array('allow',
                'users'=>array('@'),
            ),
            array('deny')
        );
    }

}