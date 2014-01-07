<?php
/**
 * Model of table "mod_article_tag".
 *
 * Columns:
 *
 * @property string $id_article
 * @property string $id_node
 * @property string $title
 * @property string $weight
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
class ArticleTag extends CActiveRecord
{
    /**
     * Список дат
     */
    public $date_created;
    public $date_updated;

    /**
     * @return string Получить имя таблицы связанное с моделью
     */
    public function tableName()
    {
        return '{{mod_article_tag}}';
    }

    /**
     * @return array Группы условий
     */
    public function scopes()
    {
        $nodeId = Yii::app()->getNodeId();

        return array(
            'node' => array(
                'condition' => 't.id_node = :id_node',
                'params' => array(
                    ':id_node' => $nodeId
                ),
            ),
            'published' => array(
                'condition' => 't.enabled = 1',
                'order' => 'weight DESC'
            )
        );
    }

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('id_node, title', 'required'),
            array('title', 'length', 'max'=>255),
            array('weight', 'type', 'type'=>'float'),
            array('enabled', 'boolean'),
        );
    }

    /**
     * @return array Правила связей между моделями
     */
    public function relations()
    {
        return array(
            'Article' => array(self::BELONGS_TO, 'Article', 'id_node'),
            'Node' => array(self::BELONGS_TO, 'Node', 'id_node'),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('site', 'Title'),
            'weight' => Yii::t('site', 'Weight'),
            'time_created' => Yii::t('site', 'Time created'),
            'date_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'date_updated' => Yii::t('site', 'Time updated'),
            'enabled' => Yii::t('site', 'Enabled')
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

        $criteria->compare('id_article_tag', $this->id_article_tag,true);
        $criteria->compare('id_node', $this->id_node, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('enabled', $this->enabled, true);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_updated', $this->time_updated, true);

        $criteria->scopes[] = 'node';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id_article_tag DESC',
                'route'=>'/tag/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            ),
            'pagination'=>array(
                'route'=>'/tag/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            )
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

    /**
     * Добавить значения дат
     */
    protected function afterFind()
    {

        $default = SettingDefault::values('datetime', 'default');
        $format = Yii::app()->getSetting('datetime', $default);

        $this->date_created   = !empty($this->time_created)?date($format, $this->time_created):null;
        $this->date_updated   = !empty($this->time_updated)?date($format, $this->time_updated):null;

        parent::afterFind();
    }

    /**
     * Добавляем значения полей время создания/обновления
     */
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