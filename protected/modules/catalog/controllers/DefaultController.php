<?php

class DefaultController extends Controller
{
    public function actionIndex(){
        Yii::app()->runController('catalog/category/index');
    }
}