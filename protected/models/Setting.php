<?php
/**
 * Model of table "db_setting".
 *
 * Column list:
 *
 * @property string $id_setting
 * @property string $title
 * @property string $value
 * @property string $time_created
 * @property string $time_updated
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class Setting extends CActiveRecord
{
    /**
     * @return string Get the table name
     */
    public function tableName()
    {
        return '{{db_setting}}';
    }

    /**
     * @return array Validation rule list
     */
    public function rules()
    {
        return array(
            array('title', 'required'),
            array('title, value', 'length', 'max'=>255),
            array('id_node, value', 'default', 'value'=>null),
            array('id_node', 'numerical'),
            array('time_created, time_updated', 'length', 'max'=>11),
            // Правило, использующиеся в search
            // @todo удалить лишние атрибуты
            array('id_setting, title, value, time_created, time_updated', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array Relation list
     */
    public function relations()
    {
        return array(
        );
    }

    /**
     * @return array Attribute label list (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_setting' => 'Id Setting',
            'group' => Yii::t('site', 'Group'),
            'title' => Yii::t('site', 'Title'),
            'value' => Yii::t('site', 'Value'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
        );
    }

    /**
     * Получить список моделей для текущего условия поиска/фильтрации
     *
     * @return CActiveDataProvider провайдер данных
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id_setting', $this->id_setting,true);
        $criteria->compare('id_node', $this->id_setting,true);
        $criteria->compare('title', $this->title,true);
        $criteria->compare('value', $this->value,true);
        $criteria->compare('time_created', $this->time_created,true);
        $criteria->compare('time_updated', $this->time_updated,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Получить статическую модуль определенную AR классом.
     *
     * @param string $className имя класса AR.
     * @return Setting статическая модель класса
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
