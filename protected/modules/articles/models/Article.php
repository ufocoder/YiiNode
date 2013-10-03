<?php
/**
 * Model of table "mod_article".
 *
 * Columns:
 *
 * @property string $id_article
 * @property string $id_node
 * @property string $title
 * @property string $value
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
     * Список дат
     */
    public $date_published;
    public $date_created;
    public $date_updated;

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/articles/';

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
    protected static $uploadUrl = '/upload/articles/';

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
            'preview' => array(
                'select' => 'id_article, id_node, time_published, slug, title, notice, image'
            ),
            'node' => array(
                'condition' => 't.id_node = :id_node',
                'params' => array(
                    ':id_node' => Yii::app()->getNodeId()
                ),
                'order' => 't.time_published DESC, t.time_created DESC'
            ),
            'published' => array(
                'condition' => 't.enabled = 1 AND t.time_published <= '. time(),
                'order' => 't.time_published DESC, t.time_created DESC'
            )
        );
    }

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('meta_title, meta_keywords, meta_description', 'default', 'value'=>null),
            array('x_image', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true),
            array('delete_image', 'boolean', 'allowEmpty'=>true),
            array('date_published', 'type', 'type'=>'datetime', 'datetimeFormat'=>'yyyy-MM-dd hh:mm'),
            array('id_node, title, notice, time_published', 'required'),
            array('title', 'length', 'max'=>255),
            array('slug', 'match', 'pattern' => '/^([^\d]\w+[\_\-\.\w]+)$|^([\d+]+[^\d]+)/i'),
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
            'notice' => Yii::t('site', 'Notice'),
            'slug' => Yii::t('site', 'Slug'),
            'meta_title' => Yii::t('site', 'Meta title'),
            'meta_keywords' => Yii::t('site', 'Meta keywords'),
            'meta_description' => Yii::t('site', 'Meta description'),
            'x_image' => Yii::t('site', 'Image'),
            'image' => Yii::t('site', 'Image'),
            'delete_image' => Yii::t('site', 'Delete image'),
            'content' => Yii::t('site', 'Content'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'time_published' => Yii::t('site', 'Time published'),
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

        $criteria->compare('id_article', $this->id_article,true);
        $criteria->compare('id_node', $this->id_node, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('time_published', $this->time_published, true);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_updated', $this->time_updated, true);

        $criteria->scopes[] = 'node';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'time_published DESC',
                'route'=>'/default/index',
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
        $format = Yii::app()->getSetting('datetimeFormat', 'Y-m-d H:i');

        $this->date_created   = !empty($this->time_created)?date($format, $this->time_created):null;
        $this->date_updated   = !empty($this->time_updated)?date($format, $this->time_updated):null;
        $this->date_published = !empty($this->time_published)?date($format, $this->time_published):null;

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
            {
                $this->time_created = time();
                if (empty($this->time_published))
                    $this->time_published = time();
            }
            else
                $this->time_updated = time();

            return true;
        }
        else
            return false;
    }

    /**
     * Удаляем файл после удаления модели
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            $filename = self::getUploadPath().$this->image;
            if (file_exists($filename) && !empty($this->image))
                unlink($filename);
            return true;
        }
    }
}