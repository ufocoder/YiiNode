<?php

class OrderItem extends CActiveRecord
{
    const DELIVERY_SELF = 1;
    const DELIVERY_CITY = 2;

    public $sendNotice;
    public $verifyCode;
    public $discount = 0;
    public $cost_discount = 0;

    public function values($setting = null, $value = null)
    {
        $deliveryPrice = Yii::app()->getSetting('orderDeliveryPrice', OrderSetting::values('orderDeliveryPrice', 'default'));

        if (!empty($deliveryPrice))
            $deliveryTitle = Yii::t('order', 'Delivery in the city ({delivery} rub.)', array('{delivery}'=>$deliveryPrice));
        else
            $deliveryTitle = Yii::t('order', 'Delivery in the city');

        $settings = array(
            "delivery_type" => array(
                "title" => array(
                    self::DELIVERY_SELF => Yii::t('order', 'Pickup'),
                    self::DELIVERY_CITY => $deliveryTitle
                ),
                "data" => array(
                    self::DELIVERY_SELF => array(
                        'title' => Yii::t('order', 'Pickup'),
                        'price' => 0
                    ),
                    self::DELIVERY_CITY => array(
                        'title' => $deliveryTitle,
                        'price' => $deliveryPrice
                    )
                )
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_order}}';
    }

    public function rules() {
        return array(
            array('delivery_type', 'in', 'range' => array_keys($this->values('delivery_type', 'title')), 'allowEmpty' => true),
            array('person_name, contact_phone, contact_email', 'length', 'max' => 150),
            array('person_name, contact_phone, contact_email, delivery_type', 'required'),
            array('contact_email', 'email'),
            array('verifyCode', 'captcha', "except"=>"ajax, update"),
            array('comment, delivery_addr', 'length', 'max' => 550),
            array('id_user_manager', 'numerical', 'allowEmpty' => true),
            array('id_user_manager', 'in', 'range'=> array_keys(User::getDropDownData(WebUser::ROLE_MANAGER)), 'allowEmpty'=>true),
            array('id_order_status', 'numerical', 'on'=>'update'),
            array('comment', 'default', 'value'=>null, 'on'=>'update'),
            array('sendNotice', 'boolean', 'allowEmpty' => true, 'on'=>'update'),

        );
    }

    public function relations() {
        return array(
            'order' => array(self::HAS_MANY, 'OrderProduct', 'id_order'),
            'product' => array(self::HAS_MANY, 'CatalogProduct', array('id_product' => 'id'), 'through' => 'order', 'joinType' => 'INNER JOIN'),
            'manager' => array(self::BELONGS_TO, 'User', 'id_user_manager'),
            'manager' => array(self::BELONGS_TO, 'User', 'id_user_manager'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_order_status' => Yii::t('site', 'Status'),
            'id_user_manager' => Yii::t('site', 'Manager'),
            'person_name' => Yii::t('site', 'Person name'),
            'contact_email' => Yii::t('site', 'Contact email'),
            'contact_phone' => Yii::t('site', 'Contact phone'),
            'discount' => Yii::t('catalog', 'Discount'),
            'delivery_addr' => Yii::t('order', 'Delivery address'),
            'delivery_type' => Yii::t('order', 'Delivery type'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'comment' => Yii::t('site', 'Comment'),
            'cost_total' => Yii::t('site', 'Summa'),
            'cost_product' => Yii::t('order', 'Total cost'),
            'verifyCode' => Yii::t('site', 'Verify code'),
            'sendNotice' => Yii::t('site', 'Notify user on change status')
        );
    }

    public function user()
    {
        $criteria = new CDbCriteria;
        $criteria->select = "o.person_name, o.contact_phone, o.contact_email,"
                           ."SUM(`o`.`cost_product`) as cost_product, `o`.`id_user`,"
                           ."SUM(ROUND((100-`d`.`discount`)/100* `o`.`cost_product`, 2)) as `cost_discount`,"
                           ."`d`.`discount`";

        $criteria->alias = 'o';
        $criteria->join = 'JOIN `{{db_user}}` `u` ON u.id_user=o.id_user '
                         .'JOIN `{{db_user_profile}}` `p` ON p.id_user=u.id_user '
                         .'LEFT JOIN `{{mod_order_discount}}` `d` ON `d`.`id_user`=`u`.`id_user`';

        $criteria->group = 'o.id_user';

        $criteria->compare('id_order', $this->id_order, true);
        $criteria->compare('id_user', $this->id_user, true);
        $criteria->compare('id_order_status', $this->id_order_status, true);
        $criteria->compare('person_name', $this->person_name);
        $criteria->compare('contact_email', $this->contact_email);
        $criteria->compare('contact_phone', $this->contact_phone);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'=>array(
                'defaultOrder' => 'o.time_created DESC, o.time_updated DESC',
            ),
        ));
    }

    public function search($limit = 10)
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id_order', $this->id_order, true);
        $criteria->compare('id_user', $this->id_user, true);
        $criteria->compare('id_order_status', $this->id_order_status, true);
        $criteria->compare('person_name', $this->person_name);
        $criteria->compare('contact_email', $this->contact_email);
        $criteria->compare('contact_phone', $this->contact_phone);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'=>array(
                'defaultOrder' => 't.time_created DESC, t.time_updated DESC',
            ),
            'pagination'=>array(
                'pageSize'=>$limit,
            )
        ));
    }

    public function recently($limit = 5) {
        $this->getDbCriteria()->mergeWith(array(
            'order' => 'time_created DESC',
            'condition' => 'enabled = 1',
            'limit' => $limit,
        ));
        return $this;
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->time_created = time();
                if (!Yii::app()->user->isGuest)
                    $this->id_user = Yii::app()->user->id;
            }
            else
                $this->time_updated = time();

            return true;
        }
        else
            return false;
    }

}