<?php

class ClientListAction extends CAction {

    public function run() {

        $class = 'OrderItem';

        $item = $class::model();
        $item->getDbCriteria()->mergeWith(array(
            'condition' => 'id_user_manager = :id_user_manager',
            'params' => array(
                ':id_user_manager' => Yii::app()->user->id
            )
        ));

        $size  = Yii::app()->getSetting('pager', OrderSetting::values('pager', 'default'));

        $criteria = $item->getDbCriteria();
        $count = $item->count();
        $criteria->limit = $size;

        $pages = new CPagination($count);
        $pages->pageSize = $size;
        $pages->applyLimit($criteria);

        $this->getController()->render('list', array(
            'list' => $item::model()->findAll($criteria),
            'pages' => $pages
        ));
    }

}