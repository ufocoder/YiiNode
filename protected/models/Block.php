<?php
/**
 * Модель таблицы "db_block".
 *
 * Список колонок:
 *
 * @property string $id_block
 * @property string $title
 * @property string $type
 * @property string $content
 * @property integer $time_created
 * @property integer $time_updated
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class Block extends CActiveRecord
{
    /**
     * Список типов
     */
    const TYPE_STRING   = 'string';
    const TYPE_TEXT     = 'text';
    const TYPE_HTML     = 'html';
    const TYPE_FILE     = 'file';

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'assets/upload/block/';

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
    protected static $uploadUrl = '/assets/upload/block/';

    /*
     * Получить физический путь загрузки документов
     */
    public static function getUploadUrl()
    {
        return self::$uploadUrl;
    }

    /**
     * @return string Получить имя таблицы связанное с моделью
     */
    public function tableName()
    {
        return '{{db_block}}';
    }

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        $rules = array(
            array('title', 'required', 'on'=>'create'),
            array('type', 'default', 'value' => self::TYPE_STRING, 'on'=>'create'),
            array('content', 'default', 'value' => null),
        );

        if ($this->type == self::TYPE_FILE){
            $rules[] = array('content', 'file');
        }

        return $rules;
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('site', 'Title'),
            'type' => Yii::t('site', 'Type'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'content' =>  Yii::t('site', 'Content'),
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

        $criteria->compare('title', $this->title, true);
        $criteria->compare('type', $this->title, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Получить статическую модуль определенную AR классом.
     *
     * @param string $className имя класса AR.
     * @return Company статическая модель класса
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

    /**
     * Добавляем значения полей время создания/обновления
     */
    public static function getValue($title, $type = null, $default = null)
    {
        if (empty($title))
            return;

        $model = self::model()->find("title=:title", array(":title"=>$title));

        if ($model === null){
            $types = array(
                self::TYPE_STRING,
                self::TYPE_TEXT,
                self::TYPE_HTML,
                self::TYPE_FILE
            );

            $model = new Block;
            $model->scenario = 'create';
            $model->title = $title;
            $model->type = in_array($type, $types)? $type : self::TYPE_STRING;
            $model->save();
        }

        if (empty($model->content) && !empty($default))
            $value = $default;
        elseif (!empty($model->content))
            $value = $model->content;

        if (!empty($value))
            switch ($type){
                case self::TYPE_FILE:
                    return $model->getUploadUrl().$value;
                break;

                case self::TYPE_HTML:
                    return $value;
                break;

                default:
                    return CHtml::encode($value);
                break;
            }

    }

}