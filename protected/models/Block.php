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
    const TYPE_IMAGE    = 'image';
    const TYPE_FLASH    = 'flash';

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/block/';

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
    protected static $uploadUrl = '/upload/block/';

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
            array('content, params', 'default', 'value' => null),
        );

        switch ($this->type)
        {
            case self::TYPE_FILE:
                $rules[] = array('content', 'file', 'on'=>'update');
            break;

            case self::TYPE_IMAGE:
                $rules[] = array('content', 'file', 'types'=>'jpg,jpeg,gif,png', 'on'=>'update');
            break;

            case self::TYPE_FLASH:
                $rules[] = array('content', 'file', 'types'=>'swf', 'on'=>'update');
            break;
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
            'params' =>  Yii::t('site', 'Params')
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
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_updated', $this->time_updated, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'title',
            )
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
    public static function getValue($title, $type = null, $default = null, $params = null)
    {
        if (empty($title))
            return;

        $theme = null;

        if (Yii::app()->Theme){
            $theme = Yii::app()->Theme->getName();
            $model = self::model()->find("title=:title AND theme=:theme", array(
                ":title" => $title,
                ":theme" => $theme
            ));
        }
        else
            $model = self::model()->find("title=:title AND theme IS NULL", array(
                ":title" => $title
            ));

        $value = null;

        if ($model === null){
            $types = array(
                self::TYPE_STRING,
                self::TYPE_TEXT,
                self::TYPE_HTML,
                self::TYPE_FILE,
                self::TYPE_IMAGE,
                self::TYPE_FLASH,
            );

            $model = new Block;
            $model->scenario = 'create';
            $model->title = $title;
            $model->theme = $theme;
            $model->type = in_array($type, $types)? $type : self::TYPE_STRING;
            $model->save();
        }

        if (!empty($model->content))
            $value = $model->content;

        if (!empty($value))
            switch ($type)
            {
                case self::TYPE_FLASH:
                    if (!empty($value)){

                        $htmlOptions = isset($params['htmlOptions'])?$params['htmlOptions']:array();
                        $htmlOptions['type'] = 'application/x-shockwave-flash';
                        $htmlOptions['data'] = $model->getUploadUrl().$value;

                        $content = null;
                        $object = CHtml::tag('object', $htmlOptions, $content);

                        return $object;
                    }
                break;

                case self::TYPE_IMAGE:
                    if (!empty($value)){
                        $src = $model->getUploadUrl().$value;
                        $alt = isset($params['alt'])?$params['alt']:null;
                        $htmlOptions = isset($params['htmlOptions'])?$params['htmlOptions']:array();
                        return CHtml::image($src, $alt, $htmlOptions);
                    }
                break;

                case self::TYPE_FILE:
                    if (!empty($value))
                        return $model->getUploadUrl().$value;
                break;

                case self::TYPE_HTML:
                    if (empty($value) && !empty($default))
                        return $default;
                    else
                        return $value;
                break;

                default:
                    if (empty($value) && !empty($default))
                        return CHtml::encode($default);
                    else
                        return CHtml::encode($value);
                break;
            }
    }

    /**
     * Удаляем файл после удаления модели
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            $filename = self::getUploadPath().$this->content;
            if (file_exists($filename))
                unlink($filename);
            return true;
        }
    }

}