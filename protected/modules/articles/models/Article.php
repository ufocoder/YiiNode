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
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'assets/upload/services/';

    /*
     * Получить путь загрузки документов
     */
    public static function getUploadPath()
    {
        return self::$uploadPath;
    }

    /**
     * Url для загрузки документов
     */
    protected static $uploadUrl = '/assets/upload/services/';

    /*
     * Получить физический путь загрузки документов
     */
    public static function getUploadUrl()
    {
        return self::$uploadUrl;
    }

    /**
     * Флаг удаления изображения
     */
    public $delete_image;

    /**
     * Экземляр файла для загрузки
     */
    public $x_image;

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
                'condition' => 't.id_node = :id_node',
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
            array('x_image', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true),
            array('delete_image', 'boolean', 'allowEmpty'=>true),
            array('id_node, title, content', 'required'),
            array('id_node', 'length', 'max'=>11),
            array('title', 'length', 'max'=>255),
            array('content, notice', 'default', 'value'=>null),
            array('enabled', 'boolean'),
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
            'x_image' => Yii::t('site', 'Image'),
            'delete_image' => Yii::t('site', 'Delete image'),
            'title' => Yii::t('site', 'Title'),
            'notice' => Yii::t('site', 'Notice'),
            'content' => Yii::t('site', 'Content'),
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
        // @todo удалить лишние атрибуты

        $criteria=new CDbCriteria;

        $criteria->compare('id_article', $this->id_article,true);
        $criteria->compare('id_node', $this->id_node,true);
        $criteria->compare('title', $this->title,true);
        $criteria->compare('content', $this->content,true);
        $criteria->compare('time_created', $this->time_created,true);
        $criteria->compare('time_updated', $this->time_updated,true);

        $criteria->scopes[] = 'node';

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
