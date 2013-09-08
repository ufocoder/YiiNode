<?php


class CatalogField extends CActiveRecord
{
    const REQUIRED_NO = 0;
    const REQUIRED_YES = 1;

    const VISIBLE_ALL = 0;
    const VISIBLE_ONLY_LIST = 0;
    const VISIBLE_ONLY_ITEM = 0;
    const VISIBLE_NO = 0;

    private $_rules = null;

    public static function values($setting = null, $value = null)
    {
        $settings = array(
            "filter" => array(
                "fields" => array('id', 'title', 'price'),
                "direction" => array('desc', 'asc'),
                "pager" => array(9, 18, 45)
            ),
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
                self::REQUIRED_YES => Yii::t('site', 'Yes'),
            ),
            'visible' => array(
                self::VISIBLE_ALL => Yii::t('site', 'For all'),
                self::VISIBLE_ONLY_LIST => Yii::t('site', 'Only for data list'),
                self::VISIBLE_ONLY_ITEM => Yii::t('site', 'Only for item data'),
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

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_catalog_fields}}';
    }

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

    public function relations()
    {
        return array(
            'product' => array(self::HAS_ONE, 'CatalogProduct', 'id_product'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('site', 'Title'),
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

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('t.id_field', $this->id_field);
        $criteria->compare('t.title', $this->title,true);
        $criteria->compare('t.varname', $this->varname);
        $criteria->compare('t.field_size', $this->field_size);
        $criteria->compare('t.field_size_min', $this->field_size_min);
        $criteria->compare('t.default', $this->default);
        $criteria->compare('t.position', $this->position);
        $criteria->compare('t.required', $this->required);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id_field DESC',
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            )
        ));
    }

}