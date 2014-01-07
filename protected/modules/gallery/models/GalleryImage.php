<?php
/**
 * Model of table "mod_gallery_image".
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
class GalleryImage extends CActiveRecord
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
     * Экземляр файла для загрузки
     */
    public $x_image;

    /**
     * @return string Получить имя таблицы связанное с моделью
     */
    public function tableName()
    {
        return '{{mod_gallery_image}}';
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
            ),
            'category' => array(
                'condition' => 't.id_gallery_category = :id_gallery_category',
            ),
            'withoutcategory' => array(
                'condition' => 't.id_gallery_category IS NULL'
            ),
            'published' => array(
                'condition' => 't.enabled = 1',
                'order' => 't.position DESC, t.id_gallery_image DESC'
            )
        );
    }

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('title', 'required'),
            array('x_image', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>!$this->isNewRecord),
            array('title', 'length', 'max'=>255),
            array('content', 'default', 'value'=>null),
            array('id_gallery_category', 'numerical'),
            array('enabled', 'boolean'),
            array('time_created, time_updated', 'length', 'max'=>10),
        );
    }

    /**
     * @return array Правила связей между моделями
     */
    public function relations()
    {
        return array(
            'Node' => array(self::BELONGS_TO, 'Node', 'id_node'),
            'Category' => array(self::BELONGS_TO, 'GalleryCategory', 'id_gallery_category'),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_gallery_category' => Yii::t('site', 'Category'),
            'title' => Yii::t('site', 'Title'),
            'content' => Yii::t('site', 'Content'),
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
    public function search($id_category = null)
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id_gallery_image', $this->id_gallery_image,true);
        $criteria->compare('id_gallery_category', $this->id_gallery_category, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_updated', $this->time_updated, true);

        $criteria->scopes[] = 'node';

        $urlParams = array(
            'nodeId' => Yii::app()->getNodeId(),
            'nodeAdmin' => true
        );

        $criteriaParams = array(
            'id_node' => Yii::app()->getNodeId(),
        );

        if (!empty($id_category)){
            $criteria->scopes[] = 'category';
            $urlParams['id_category'] = $id_category;
            $criteriaParams['id_gallery_category'] = $id_category;
        }

        $criteria->with = array('Category');
        $criteria->params = $criteriaParams;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.id_gallery_category DESC, t.position DESC, t.id_gallery_image DESC',
                'route'=>'/default/index',
                'params'=>$urlParams
            ),
            'pagination'=>array(
                'route'=>'/default/index',
                'params'=>$urlParams
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
     * Поднять изображение в списке
     */
    public function moveUp()
    {
        if (!empty($this->id_gallery_category))
            $near = self::model()->find(array(
                'condition' => 'position >= :position AND id_gallery_category = :id_gallery_category AND id_gallery_image != :id_gallery_image',
                'order' => 'position ASC',
                'params' => array(
                    ':position' => $this->position,
                    ':id_gallery_category' => $this->id_gallery_category,
                    ':id_gallery_image' => $this->id_gallery_image
                )
            ));
        else
            $near = self::model()->find(array(
                'condition' => 'position >= :position AND id_gallery_category IS NULL AND id_gallery_image != :id_gallery_image',
                'order' => 'position ASC',
                'params' => array(
                    ':position' => $this->position,
                    ':id_gallery_image' => $this->id_gallery_image
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
     * Опустить изображение в списке
     */
    public function moveDown()
    {
        if (!empty($this->id_gallery_category))
            $near = self::model()->find(array(
                'condition' => 'position <= :position AND id_gallery_category = :id_gallery_category AND id_gallery_image != :id_gallery_image',
                'order' => 'position DESC',
                'params' => array(
                    ':position' => $this->position,
                    ':id_gallery_category' => $this->id_gallery_category,
                    ':id_gallery_image' => $this->id_gallery_image
                )
            ));
        else
            $near = self::model()->find(array(
                'condition' => 'position <= :position AND id_gallery_category IS NULL AND id_gallery_image != :id_gallery_image',
                'order' => 'position DESC',
                'params' => array(
                    ':position' => $this->position,
                    ':id_gallery_image' => $this->id_gallery_image
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
        if (!empty($this->id_gallery_category))
            $list = self::model()->findAll(array(
                'condition' => 'id_gallery_category = :id_gallery_category',
                'order' => 'position ASC',
                'params' => array(
                    ':id_gallery_category' => $this->id_gallery_category
                )
            ));
        else
            $list = self::model()->findAll(array(
                'condition' => 'id_gallery_category IS NULL',
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