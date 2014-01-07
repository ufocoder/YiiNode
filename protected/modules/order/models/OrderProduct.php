<?php


class OrderProduct extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{mod_order_product}}';
    }

    public function rules() {
        return array(
            array('id_order, id_product, count, price', 'required'),
            array('id_field_size, id_product, count', 'numerical', 'integerOnly' => true, 'min' => 0),
            array('price', 'numerical')
        );
    }

    public function relations() {
        return array(
            //'product' => array(self::MANY_MANY, 'modules\catalog\models\Product', '{{order_product}}(id_order, id_product)')
        );
    }

    public function attributeLabels() {
        return array(
            'id_order' => Yii::t('order', 'Order'),
            'id_status' => Yii::t('order', 'Status'),
            'id_product' => Yii::t('order', 'Product'),
            'id_size' => Yii::t('order', 'Size'),
            'count' => Yii::t('order', 'Count'),
            'price' => Yii::t('order', 'Order'),
            'sendNotice' => Yii::t('order', 'Send notice'),
        );
    }

    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('id_order', $this->id_order, true);
        $criteria->compare('id_product', $this->id_product, true);
        $criteria->compare('id_field_size', $this->id_field_size, true);
        $criteria->compare('count', $this->comment, true);
        $criteria->compare('price', $this->count, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'=>array(
                'defaultOrder'=>'id DESC',
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            ),
            'pagination'=>array(
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            )
        ));
    }

}