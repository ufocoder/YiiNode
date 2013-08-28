<?php
/**
 * Redirect Model
 */
class RedirectSetting extends CFormModel
{
    public $nodeId;
    public $url;

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('nodeId', 'numerical'),
            array('nodeId', 'nodeCheck'),
            array('url', 'default', 'value'=>null),
            array('url', 'match', 'pattern'=>'/^http(s)?:\/\/[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i'),
            array('url', 'length', 'max'=>255)
        );
    }

    public function nodeCheck($attribute, $params)
    {
        $node = Yii::app()->getNodeByID($this->$attribute);
        if ($node['module'] == 'redirect')
            $this->addError($attribute, Yii::t('site', 'Redirect node couldn\'t refer to \'Redirect\' module!'));
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'nodeId' => Yii::t('site', 'Node'),
            'url' => Yii::t('site', 'URL'),
        );
    }

}