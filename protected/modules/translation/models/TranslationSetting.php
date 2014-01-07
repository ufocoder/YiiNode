<?php
/**
 * Redirect Model
 */
class TranslationSetting extends CFormModel
{
    public $nodeId;

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('nodeId', 'numerical'),
            array('nodeId', 'nodeCheck'),
        );
    }

    public function nodeCheck($attribute, $params)
    {
        $node = Yii::app()->getNodeByID($this->$attribute);
        if ($node['module'] == 'redirect')
            $this->addError($attribute, Yii::t('site', 'Transcation node couldn\'t refer to \'Redirect\' module!'));
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'nodeId' => Yii::t('site', 'Node'),
        );
    }

}