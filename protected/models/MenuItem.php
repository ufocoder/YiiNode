<?php
/**
 * Model of table "db_menu_item".
 *
 * Column list:
 *
 * @property string $id_menu_list
 * @property string $title
 * @property string $slug
 * @property string $time_created
 * @property string $time_updated
 *
 * Relation list:
 *
 * @property MenuList $Parent
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class MenuItem extends CActiveRecord
{

    /**
     * Флаг удаления изображения
     */
    public $delete_image;

    /**
     * Экземляр файла для загрузки
     */
    public $x_image;

    /**
     * Флаг удаления изображения
     */
    public $delete_icon;

    /**
     * Экземляр файла для загрузки
     */
    public $x_icon;

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/menu/';

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
        return '{{db_menu_item}}';
    }

    /**
     * @return array Validation rule list
     */
    public function rules()
    {
        return array(
            array('title, id_menu_list', 'required'),
            array('title, alttitle, url', 'length', 'max'=>255),
            array('alttitle, url, icon, image, notice', 'default', 'value'=>null),
            array('enabled', 'boolean'),
            array('id_menu_list, id_node, position', 'numerical'),
            array('position, time_created, time_updated', 'length', 'max'=>11),
            array('x_image, x_icon', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true),
            array('delete_image, delete_icon', 'boolean', 'allowEmpty'=>true),
            // search
            array('id_menu_list, title, slug, time_created, time_updated', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array Relation list
     */
    public function relations()
    {
        return array(
            'list' => array(self::BELONGS_TO, 'MenuList', 'id_menu_list'),
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
                'order' => 't.position'
            ),
        );
    }

    public function menu($listSlug)
    {
        $this->getDbCriteria()->mergeWith(array(
            'with' => 'list',
            'condition' => 'list.slug = :listSlug',
            'params' => array(
                ':listSlug' => $listSlug
            )
        ));
        return $this;
    }

    /**
     * @return array Attribute label list (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_menu_list' => Yii::t('site', 'Menu'),
            'id_node' => Yii::t('site', 'Node'),
            'title' => Yii::t('site', 'Title'),
            'alttitle' => Yii::t('site', 'Alternative title'),
            'url' => Yii::t('site', 'Url'),
            'icon' => Yii::t('site', 'Icon'),
            'image' => Yii::t('site', 'Image'),
            'notice' => Yii::t('site', 'Notice'),
            'position' => Yii::t('site', 'Position'),
            'delete_image' => Yii::t('site', 'Delete image'),
            'delete_icon' => Yii::t('site', 'Delete icon'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'enabled' => Yii::t('site', 'Enabled'),
        );
    }

    /**
     * Получить список моделей для текущего условия поиска/фильтрации
     *
     * @return CActiveDataProvider провайдер данных
     */
    public function search($id_menu_list)
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id_menu_item', $this->id_menu_item,true);
        $criteria->compare('id_menu_list', $this->id_menu_list,true);
        $criteria->compare('id_node', $this->id_node,true);
        $criteria->compare('title', $this->title,true);
        $criteria->compare('url', $this->url,true);
        $criteria->compare('notice', $this->notice,true);
        $criteria->compare('position', $this->position,true);
        $criteria->compare('time_created', $this->time_created,true);
        $criteria->compare('time_updated', $this->time_updated,true);
        $criteria->compare('enabled', $this->enabled,true);

        $criteria->condition = "id_menu_list = :id_menu_list";
        $criteria->params = array(
            ':id_menu_list' => $id_menu_list
        );

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'position',
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