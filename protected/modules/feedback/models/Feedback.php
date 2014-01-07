<?php

class Feedback extends CActiveRecord
{
    /**
     * Список дат
     */
    public $date_created;
    public $date_updated;
    public $date_readed;

    /**
     * Проверочный код
     */
    public $verifyCode;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_feedback}}';
    }

    public function rules()
    {
        return array(
            array('person_name, contact_email, content', 'required'),
            array('contact_phone', 'numerical', 'allowEmpty'=>true),
            array('content', 'length', 'min'=>3),
            array('contact_email', 'email'),
            array('contact_phone', 'default', 'value'=>null),
            array('verifyCode', 'captcha', 'allowEmpty'=>false, 'except'=>'ajax')
        );
    }

    /**
     * @return array Правила связей между моделями
     */
    public function relations()
    {
        return array(
            'Node' => array(self::BELONGS_TO, 'Node', 'id_node'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'person_name' => Yii::t('site', 'Full name'),
            'contact_email' => Yii::t('site', 'Contact e-mail'),
            'contact_phone' => Yii::t('site', 'Contact phone'),
            'content' => Yii::t('site', 'Content'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'time_readed' => Yii::t('site', 'Time readed'),
            'verifyCode' => Yii::t('site', 'Verify code'),
            'enabled' => Yii::t('site', 'Enabled')
        );
    }

    public function search($nodeId = null, $limit = 10)
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id_feedback',$this->id_feedback);
        $criteria->compare('person_name', $this->person_name, true);
        $criteria->compare('contact_email', $this->contact_email, true);
        $criteria->compare('contact_phone', $this->contact_phone, true);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_readed', $this->time_readed, true);
        $criteria->compare('time_updated', $this->time_updated, true);

        if (!empty($nodeId))
            $criteria->scopes[] = 'node';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id_feedback DESC',
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            ),
            'pagination'=>array(
                'pageSize'=>$limit,
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            )
        ));
    }

    /**
     * @return array Группы условий
     */
    public function scopes()
    {
        return array(
            'node' => array(
                'condition' => 't.id_node = :id_node',
                'params' => array(
                    ':id_node' => Yii::app()->getNodeId()
                ),
                'order' => 't.time_created DESC'
            )   ,
            'notreaded' => array(
                'condition' => ' t.time_readed is NULL'
            )
        );
    }

    /**
     * Добавить значения дат
     */
    protected function afterFind()
    {
        $format = Yii::app()->getSetting('datetimeFormat', 'Y-m-d H:i');

        $this->date_created = !empty($this->time_created)?date($format, $this->time_created):null;
        $this->date_updated = !empty($this->time_updated)?date($format, $this->time_updated):null;
        $this->date_readed = !empty($this->time_readed)?date($format, $this->time_readed):null;

        parent::afterFind();
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
                $this->time_created = time();
            else
                $this->time_updated = time();
            return true;
        }
        else
            return false;
    }

}