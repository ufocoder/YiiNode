<?php
/**
 * Model of table "db_menu_list".
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
 * @property MenuItem[] $items
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class MenuList extends CActiveRecord
{
    /**
     * @return string Get the table name
     */
    public function tableName()
    {
        return '{{db_menu_list}}';
    }

    /**
     * @return array Validation rule list
     */
    public function rules()
    {
        return array(
            array('title, slug', 'required'),
            array('title, slug', 'length', 'max'=>255),
            array('notice', 'default', 'value'=>null),
            array('time_created, time_updated', 'length', 'max'=>11),
            array('id_menu_list, title, slug, time_created, time_updated', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array Relation list
     */
    public function relations()
    {
        return array(
            'items' => array(self::HAS_MANY, 'MenuItem', 'id_menu_list'),
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
            'slug' => Yii::t('site', 'Slug'),
            'notice' => Yii::t('site', 'Notice'),
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
        $criteria=new CDbCriteria;

        $criteria->compare('id_menu_list', $this->id_menu_list,true);
        $criteria->compare('title', $this->title,true);
        $criteria->compare('slug', $this->slug,true);
        $criteria->compare('notice', $this->notice,true);
        $criteria->compare('time_created', $this->time_created,true);
        $criteria->compare('time_updated', $this->time_updated,true);

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