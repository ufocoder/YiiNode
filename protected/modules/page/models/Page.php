<?php
/**
 * Model of table "mod_page".
 *
 * Columns
 *
 * @property string $id_node
 * @property string $title
 * @property string $content
 *
 * Relation list:
 *
 * @property Node $Node
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class Page extends CActiveRecord
{
    /**
     * @return string Получить имя таблицы связанное с моделью
     */
    public function tableName()
    {
        return '{{mod_page}}';
    }

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('id_node', 'numerical'),
            array('content', 'default', 'value'=>null),
            array('title', 'length', 'max'=>255),
            // Правило, использующиеся в search
            // @todo удалить лишние атрибуты
            array('id_node, title, content', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array Правила связей между моделями
     */
    public function relations()
    {
        return array(
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_node' => 'Id Node',
            'title' => Yii::t('site', 'Title'),
            'content' => Yii::t('site', 'Content'),
        );
    }

    /**
     * Получить список моделей для текущего условия поиска/фильтрации
     *
     * @return CActiveDataProvider провайдер данных
     */
    public function search()
    {
        // @todo удалить лишние атрибуты

        $criteria=new CDbCriteria;

        $criteria->compare('id_node', $this->id_node,true);
        $criteria->compare('title', $this->title,true);
        $criteria->compare('content', $this->content,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Получить статическую модуль определенную AR классом.
     *
     * @param string $className имя класса AR.
     * @return Page статическая модель класса
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
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
