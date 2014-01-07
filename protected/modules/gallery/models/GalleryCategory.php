<?php
/**
 * Model of table "mod_gallery_category".
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
class GalleryCategory extends CActiveRecord
{
    /**
     * Список дат
     */
    public $date_created;
    public $date_updated;

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/gallery/';

    /*
     * Получить путь загрузки документов
     */
    public static function getUploadPath()
    {
        return self::$uploadPath;
    }

    /**
     * Количество изображений в категории
     */
    public $count = 0;

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
        return '{{mod_gallery_category}}';
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
                'order' => 't.time_created DESC'
            ),
            'published' => array(
                'condition' => 't.enabled = 1'
            )
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
            array('id_node, title', 'required'),
            array('title', 'length', 'max'=>255),
            array('content', 'default', 'value'=>null),
            array('enabled', 'boolean'),
            array('time_created, time_updated', 'length', 'max'=>10),
            // Правило, использующиеся в search
            // @todo удалить лишние атрибуты
            array('id_gallery_category, id_node, title, content, time_created, time_updated', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array Правила связей между моделями
     */
    public function relations()
    {
        return array(
            'Parent' => array(self::BELONGS_TO, 'Node', 'id_node'),
            'Count'  => array(self::STAT, 'GalleryImage', 'id_gallery_category')
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('site', 'Title'),
            'content' => Yii::t('site', 'Content'),
            'count' => Yii::t('site', 'Count of images'),
            'meta_keywords' => Yii::t('site', 'Meta keywords'),
            'meta_description' => Yii::t('site', 'Meta description'),
            'x_image' => Yii::t('site', 'Image'),
            'image' => Yii::t('site', 'Image'),
            'delete_image' => Yii::t('site', 'Delete image'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
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
        // @todo удалить лишние атрибуты

        $criteria=new CDbCriteria;

        $criteria->compare('id_gallery_category', $this->id_gallery_category,true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_updated', $this->time_updated, true);

        $criteria->scopes[] = 'node';

        $criteria->with = array('Count');

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.position DESC',
                'route'=>'/category/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            ),
            'pagination'=>array(
                'route'=>'/category/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            )
        ));
    }

    /**
     * Поднять в списке
     */
    public function moveUp()
    {
        $near = self::model()->find(array(
            'condition' => 'position >= :position AND id_gallery_category != :id_gallery_category',
            'order' => 'position ASC',
            'params' => array(
                ':position' => $this->position,
                ':id_gallery_category' => $this->id_gallery_category
            )
        ));

        if (empty($near))
            return true;

        $position = $this->position;

        $this->saveAttributes(array(
            'position' => $near->position
        ));

        $near->saveAttributes(array(
            'position' => $position
        ));

        $this->updatePosition();
    }

    /**
     * Опустить в списке
     */
    public function moveDown()
    {
        $near = self::model()->find(array(
            'condition' => 'position <= :position AND id_gallery_category != :id_gallery_category',
            'order' => 'position DESC',
            'params' => array(
                ':position' => $this->position,
                ':id_gallery_category' => $this->id_gallery_category
            )
        ));

        if (empty($near))
            return true;

        $position = $this->position;

        $this->saveAttributes(array(
            'position' => $near->position
        ));

        $near->saveAttributes(array(
            'position' => $position
        ));

        return $this->updatePosition();
    }

    /**
     * Обновить список позиций
     */
    protected function updatePosition()
    {
        $list = self::model()->findAll(array(
            'order' => 'position ASC'
        ));

        $position = 0;

        foreach($list as $item){
            $item->saveAttributes(array(
                'position' => $position
            ));
            $position++;
        }

        return true;
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

        $this->date_created = !empty($this->time_created)?date($format, $this->time_created):null;
        $this->date_updated = !empty($this->time_updated)?date($format, $this->time_updated):null;

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