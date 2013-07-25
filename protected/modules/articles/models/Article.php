<?php
/**
 * Model of table "mod_article".
 *
 * Columns:
 *
 * @property string $id_article
 * @property string $id_node
 * @property string $title
 * @property string $content
 * @property string $time_created
 * @property string $time_updated
 *
 * Relation list:
 *
 * @property Node $Node
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class Article extends CActiveRecord
{
    /**
     * @return string Получить имя таблицы связанное с моделью
     */
    public function tableName()
    {
        return '{{mod_article}}';
    }

    /**
     * @return array Группы условий
     */
    public function scopes(){
        return array(
            'node' => array(
                'condition' => 't.id_node = :id_node and t.enabled = 1',
                'params' => array(
                    ':id_node' => Yii::app()->getNodeId()
                ),
                'order' => 't.time_published'
            ),
        );
    }

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('id_node, title, content', 'required'),
            array('id_node', 'length', 'max'=>11),
            array('title, content', 'length', 'max'=>255),
            array('time_created, time_updated', 'length', 'max'=>10),
            // Правило, использующиеся в search
            // @todo удалить лишние атрибуты
            array('id_article, id_node, title, content, time_created, time_updated', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array Правила связей между моделями
     */
    public function relations()
    {
        return array(
            'Parent' => array(self::BELONGS_TO, 'Node', 'id_node'),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_article' => 'Id Article',
            'id_node' => 'Id Node',
            'title' => 'Title',
            'content' => 'Content',
            'time_created' => 'Time Created',
            'time_updated' => 'Time Updated',
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

        $criteria->compare('id_article', $this->id_article,true);
        $criteria->compare('id_node', $this->id_node,true);
        $criteria->compare('title', $this->title,true);
        $criteria->compare('content', $this->content,true);
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
     * @return Article статическая модель класса
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
