<?php

class DefaultController extends ControllerAdmin
{
    public function actionIndex()
    {
        Yii::app()->runController('user/admin/admin');
    }
}