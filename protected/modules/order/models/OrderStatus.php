<?php

class OrderStatus extends CActiveRecord {
    // delivery type

    const DELIVERY_USER = 1;
    const DELIVERY_COMPANY = 2;

    // status list
    const STATUS_REFUSED = -1;
    const STATUS_UNDEFINED = 0;
    const STATUS_SEND = 1;
    const STATUS_APPROVED = 2;
    const STATUS_TRANSIT = 3;
    const STATUS_COMPLETED = 4;

    public static function getStatus($id_status = null)
    {
        $status_list = array(
            self::STATUS_REFUSED => Yii::t('order', 'Refused'),
            self::STATUS_UNDEFINED => Yii::t('order', 'Undefined'),
            self::STATUS_SEND => Yii::t('order', 'Sended'),
            self::STATUS_APPROVED => Yii::t('order', 'Approved'),
            self::STATUS_TRANSIT => Yii::t('order', 'Transit'),
            self::STATUS_COMPLETED => Yii::t('order', 'Completed')
        );

        if (isset($id_status) && $id_status != null)
            return $status_list[$id_status];
        else
            return $status_list;
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{mod_order_status}}';
    }

    public function rules() {
        return array(
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id_order' => Yii::t('order', 'Order'),
            'id_status' => Yii::t('order', 'Status'),
            'comment' => Yii::t('order', 'Comment'),
            'time_created' => Yii::t('order', 'Time created'),
        );
    }

    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('id_order', $this->id_order, true);
        $criteria->compare('id_order_status', $this->id_order_status, true);
        $criteria->compare('comment', $this->comment);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('send_notice', $this->send_notice, true);
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 'time_created DESC',
                    ),
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->time_created = time();
            }
            else
                $this->time_updated = time();

            return true;
        }
        else
            return false;
    }

}