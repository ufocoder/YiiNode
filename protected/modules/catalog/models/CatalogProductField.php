<?php

class CatalogProductField extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_catalog_product_fields}}';
    }

    public function rules()
    {
        $rules = array();

        $required = array();
        $numerical = array();
        $float = array();
        $decimal = array();

        $fields = CatalogProduct::model()->getFields();

        foreach ($fields as $varname => $field) {

            $field_rule = array();
            if ($field->required==1)
                $required [] = $field->varname;

            // field_type
            if ($field->field_type=='FLOAT')
                $float[] = $field->varname;

            if ($field->field_type=='DECIMAL')
                $decimal[] = $field->varname;

            if ($field->field_type=='INTEGER')
                $numerical[] = $field->varname;

            if ($field->field_type=='VARCHAR'||$field->field_type=='TEXT') {
                $field_rule = array($field->varname, 'length', 'max'=>$field->field_size, 'min' => $field->field_size_min);
                $rules[] = $field_rule;
            }

            if ($field->field_type=='DATE') {
                $field_rule = array($field->varname, 'type', 'type' => 'date', 'dateFormat' => 'yyyy-mm-dd', 'allowEmpty'=>true);
                $rules[] = $field_rule;
            }
        }

        $rules[] = array(implode(',',$required), 'required');
        $rules[] = array(implode(',',$numerical), 'numerical', 'integerOnly'=>true);
        $rules[] = array(implode(',',$float), 'type', 'type'=>'float');
        $rules[] = array(implode(',',$decimal), 'match', 'pattern' => '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/');

        return $rules;
    }

    public function relations()
    {
        return array(
            'product' => array(self::HAS_ONE, 'modules\catalog\models\Product', 'id'),
        );
    }

    public function attributeLabels()
    {
        $fields = CatalogProduct::model()->getFields();
        $labels = CatalogProduct::model()->attributeLabels();

        foreach ($fields as $varname => $field)
            $labels[$varname] = $field->title;

        return $labels;

    }

    public function scopes(){
        return array(
            'published' => array(
                'condition' => 't.enabled = 1',
            ),
            'recently' => array(
                'condition' => 't.enabled = 1',
                'limit' => 10
            ),
        );
    }

}