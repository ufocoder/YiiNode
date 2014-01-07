<?php
/**
 * Model of table "mod_slider".
 *
 * Column list:
 *
 * @property integer $id_slider
 * @property string $title
 * @property string $content
 * @property string $image
 * @property integer $time_created
 * @property integer $time_updated
 * @property integer $position
 * @property integer $enabled
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class Slider extends CActiveRecord
{
    /**
     * Экземляр файла для загрузки
     */
    public $x_image;

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/slider/';

    /*
     * Получить путь загрузки документов
     */
    public static function getUploadPath()
    {
        return self::$uploadPath;
    }

    /**
     * @return string Get the table name
     */
    public function tableName()
    {
        return '{{mod_slider}}';
    }

    /**
     * @return array Validation rule list
     */
    public function rules()
    {
        return array(
            // default
            array('title', 'required'),
            array('background', 'length', 'max'=>124),
            array('content', 'default', 'setOnEmpty'=>true, 'value'=>''),
            array('x_image', 'file', 'allowEmpty'=>!$this->isNewRecord),
            array('enabled', 'boolean'),
            array('position', 'numerical'),
        );
    }

    /**
     * @return array Scopes
     */
    public function scopes()
    {
        return array(
            'active' => array(
                'condition' => 't.enabled = 1',
                'order' => 't.position DESC'
            ),
        );
    }

    /**
     * @return array Attribute label list (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('site', 'Title'),
            'background' => Yii::t('slider', 'Background color'),
            'content' => Yii::t('site', 'Content'),
            'position' => Yii::t('site', 'Position'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'x_image' => Yii::t('site', 'Image'),
            'delete_image'=> Yii::t('site', 'Delete image'),
            'image' => Yii::t('site', 'Image'),
            'enabled' => Yii::t('site', 'Enabled'),
        );
    }

    /**
     * Поднять в списке
     */
    public function moveUp()
    {
        $near = self::model()->find(array(
            'condition' => 'position >= :position AND id_slider != :id_slider',
            'order' => 'position ASC',
            'params' => array(
                ':position' => $this->position,
                ':id_slider' => $this->id_slider
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
            'condition' => 'position <= :position AND id_slider != :id_slider',
            'order' => 'position DESC',
            'params' => array(
                ':position' => $this->position,
                ':id_slider' => $this->id_slider
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
     * Получить список моделей для текущего условия поиска/фильтрации
     *
     * @return CActiveDataProvider провайдер данных
     */
    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id_slider', $this->id_slider);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('image', $this->time_created, true);
        $criteria->compare('image', $this->time_updated, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('enabled', $this->enabled, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.position DESC',
            )
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