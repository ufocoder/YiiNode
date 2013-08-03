<?php

class Profile extends CActiveRecord
{
    const GROUP_FIELD_ALL = 1;
    const GROUP_FIELD_REGISTRATION = 2;

    private $_rules = null;

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
        return '{{db_user_profile}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        if ($this->_rules !== null)
        {
            $required   = array();
            $numerical  = array();
            $float      = array();
            $decimal    = array();
            $rules      = array();

            $model = $this->getFields();

            foreach ($model as $field)
            {
                $field_rule = array();

                if ($field->required == ProfileField::REQUIRED_YES_NOT_SHOW_REG || $field->required==ProfileField::REQUIRED_YES_SHOW_REG)
                    array_push($required,$field->varname);

                if ($field->field_type == 'FLOAT')
                    array_push($float, $field->varname);

                if ($field->field_type=='DECIMAL')
                    array_push($decimal, $field->varname);

                if ($field->field_type=='INTEGER')
                    array_push($numerical, $field->varname);

                if ($field->field_type == 'VARCHAR' || $field->field_type=='TEXT')
                {
                    $field_rule = array(
                        $field->varname,
                        'length',
                        'max'=>$field->field_size,
                        'min' => $field->field_size_min
                    );
                    if ($field->error_message)
                        $field_rule['message'] = Yii::t('site', $field->error_message);
                    array_push($rules, $field_rule);
                }

                if ($field->field_type=='DATE') {
                    $field_rule = array($field->varname, 'type', 'type' => 'date', 'dateFormat' => 'yyyy-mm-dd', 'allowEmpty'=>true);
                    if ($field->error_message)
                        $field_rule['message'] = Yii::t('site', $field->error_message);
                    array_push($rules,$field_rule);
                }

                if ($field->match) {
                    $field_rule = array(
                        $field->varname,
                        'match',
                        'pattern' => $field->match
                    );
                    if ($field->error_message)
                        $field_rule['message'] = Yii::t('site', $field->error_message);
                    array_push($rules,$field_rule);
                }

                if ($field->range)
                {
                    $rules = explode(';', $field->range);
                    for ($i=0;$i<count($rules);$i++)
                        $rules[$i] = current(explode("==",$rules[$i]));

                    $field_rule = array(
                        $field->varname,
                        'in',
                        'range' => self::rangeRules($field->range)
                    );
                    if ($field->error_message)
                        $field_rule['message'] = Yii::t('site', $field->error_message);
                    array_push($rules,$field_rule);
                }
            }

            array_push($rules, array(implode(',', $required), 'required'));
            array_push($rules, array(implode(',', $numerical), 'numerical', 'integerOnly'=>true));
            array_push($rules, array(implode(',', $float), 'type', 'type'=>'float'));
            array_push($rules, array(implode(',', $decimal), 'match', 'pattern' => '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/'));
            $this->_rules = $rules;
        }
        return $this->_rules;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'id_user'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        $fields = $this->getFields();

        foreach ($fields as $field)
            $labels[$field->varname] = $field->title;

        return $labels;
    }

    private function rangeRules($str) {

        return $rules;
    }

    static public function range($str,$fieldValue=NULL) {
        $rules = explode(';',$str);
        $array = array();
        for ($i=0;$i<count($rules);$i++) {
            $item = explode("==",$rules[$i]);
            if (isset($item[0])) $array[$item[0]] = ((isset($item[1]))?$item[1]:$item[0]);
        }
        if (isset($fieldValue))
            if (isset($array[$fieldValue])) return $array[$fieldValue]; else return '';
        else
            return $array;
    }

    public function widgetAttributes()
    {
        $data = array();
        $fields = $this->getFields();
        foreach ($fields as $field)
            if ($field->widget)
                $data[$field->varname]=$field->widget;

        return $data;
    }

    public function widgetParams($fieldName)
    {
        $data = array();
        $fields = $this->getFields();
        foreach ($model as $field)
            if ($field->widget)
                $data[$field->varname] = $field->widgetparams;

        return $data[$fieldName];
    }

    public function getFields($group = null)
    {
        $model = ProfileField::model();

        switch($group){
            default:
            case self::GROUP_FIELD_ALL:
                $scope = null;
            break;

            case  self::GROUP_FIELD_REGISTRATION:
                $scope = 'registration';
            break;
        }

        if (!empty($scope))
            return $model->$scope()->findAll();
        else
            return $model->findAll();
    }
}