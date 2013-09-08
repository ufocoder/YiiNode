<?php

class ProfileField extends CActiveRecord
{
    const VISIBLE_NO = 0;
    const VISIBLE_ALL = 1;
    const VISIBLE_ONLY_OWNER =2;
    const VISIBLE_REGISTER_USER =3;

    const REQUIRED_NO = 0;
    const REQUIRED_NO_SHOW_REG = 2;
    const REQUIRED_YES_SHOW_REG = 1;
    const REQUIRED_YES_NOT_SHOW_REG = 3;

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{db_user_field}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('varname, title, field_type', 'required'),
            array('varname', 'match', 'pattern' => '/^[A-Za-z_0-9]+$/u','message' => Yii::t('site', "Variable name may consist of A-z, 0-9, underscores, begin with a letter.")),
            array('varname', 'unique', 'message' => Yii::t('site', "This field already exists.")),
            array('varname, field_type', 'length', 'max'=>50),
            array('field_size_min, required, position, visible', 'numerical', 'integerOnly'=>true),
            array('field_size', 'match', 'pattern' => '/^\s*[-+]?[0-9]*\,*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/'),
            array('title, match, error_message, default', 'length', 'max'=>255),
            array('range', 'length', 'max'=>5000),
            array('id, varname, title, field_type, field_size, field_size_min, required, match, range, error_message, default, position, visible', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_user_field' => Yii::t('site', 'Id'),
            'varname' => Yii::t('site', 'Variable name'),
            'title' => Yii::t('site', 'Title'),
            'field_type' => Yii::t('site', 'Field type'),
            'field_size' => Yii::t('site', 'Field size'),
            'field_size_min' => Yii::t('site', 'Field size min'),
            'required' => Yii::t('site', 'Required'),
            'match' => Yii::t('site', 'Match'),
            'range' => Yii::t('site', 'Range'),
            'error_message' => Yii::t('site', 'Error message'),
            'default' => Yii::t('site', 'Default'),
            'position' => Yii::t('site', 'Position'),
            'visible' => Yii::t('site', 'Visible'),
        );
    }

    public function scopes()
    {
        return array(
            'forAll'=>array(
                'condition'=>'visible='.self::VISIBLE_ALL,
                'order'=>'position',
            ),
            'forUser'=>array(
                'condition'=>'visible>='.self::VISIBLE_REGISTER_USER,
                'order'=>'position',
            ),
            'forOwner'=>array(
                'condition'=>'visible>='.self::VISIBLE_ONLY_OWNER,
                'order'=>'position',
            ),
            'forRegistration'=>array(
                'condition'=>'required='.self::REQUIRED_NO_SHOW_REG.' OR required='.self::REQUIRED_YES_SHOW_REG,
                'order'=>'position',
            ),
            'sort'=>array(
                'order'=>'position',
            ),
        );
    }

    public static function values($setting = null, $value = null)
    {
        if (empty($setting))
            return false;

        $settings = array(
            'field_type' => array(
                'INTEGER' => Yii::t('site', 'INTEGER'),
                'VARCHAR' => Yii::t('site', 'VARCHAR'),
                'TEXT'=> Yii::t('site', 'TEXT'),
                'DATE'=> Yii::t('site', 'DATE'),
                'FLOAT'=> Yii::t('site', 'FLOAT'),
                'DECIMAL'=> Yii::t('site', 'DECIMAL'),
                'BOOL'=> Yii::t('site', 'BOOL'),
                'BLOB'=> Yii::t('site', 'BLOB'),
                'BINARY'=> Yii::t('site', 'BINARY'),
            ),
            'required' => array(
                self::REQUIRED_NO => Yii::t('site', 'No'),
                self::REQUIRED_NO_SHOW_REG => Yii::t('site', 'No, but show on registration form'),
                self::REQUIRED_YES_SHOW_REG => Yii::t('site', 'Yes and show on registration form'),
                self::REQUIRED_YES_NOT_SHOW_REG => Yii::t('site', 'Yes'),
            ),
            'visible' => array(
                self::VISIBLE_ALL => Yii::t('site', 'For all'),
                self::VISIBLE_REGISTER_USER => Yii::t('site', 'Registered users'),
                self::VISIBLE_ONLY_OWNER => Yii::t('site', 'Only owner'),
                self::VISIBLE_NO => Yii::t('site', 'Hidden'),
            ),
        );

        if ($value == null && isset($settings[$setting]))
            return $settings[$setting];
        else if ($value != null && isset($settings[$setting][$value]))
            return $settings[$setting][$value];
        else
            return false;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id_user_field', $this->id_user_field);
        $criteria->compare('varname',$this->varname,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('field_type',$this->field_type,true);
        $criteria->compare('field_size',$this->field_size);
        $criteria->compare('field_size_min',$this->field_size_min);
        $criteria->compare('required',$this->required);
        $criteria->compare('match',$this->match,true);
        $criteria->compare('range',$this->range,true);
        $criteria->compare('error_message',$this->error_message,true);
        $criteria->compare('default',$this->default,true);
        $criteria->compare('position',$this->position);
        $criteria->compare('visible',$this->visible);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'pagination'=>array(
              //  'pageSize'=>Yii::app()->controller->module->fields_page_size,
            ),
            'sort'=>array(
                'defaultOrder'=>'position',
            ),
        ));
    }
}