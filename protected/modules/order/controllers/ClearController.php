<?php

class ClearController extends Controller
{

    public function actionIndex()
    {
        BasketAttrProduct::clearCurrent();
        $this->redirect(Yii::app()->createUrl('order'));
    }

}