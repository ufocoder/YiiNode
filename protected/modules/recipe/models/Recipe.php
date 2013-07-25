<?php
/**
 * Модель таблицы "mod_recipe".
 *
 * Список колонок:
 *
 * @property string $id_recipe
 * @property string $id_recipe_category
 * @property string $title
 * @property string $image
 * @property integer $time
 * @property integer $person
 * @property string $notice
 * @property string $content
 *
 * Список связей:
 *
 * @property RecipeCategory $RecipeCategory
 * @property RecipeImage[] $RecipeImages
 * @property RecipeStep[] $RecipeStep
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class Recipe extends CActiveRecord
{
    public $x_image;

    /**
     * @return string Получить имя таблицы связанное с моделью
     */
    public function tableName()
    {
        return 'mod_recipe';
    }

    /**
     * @return array Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('title', 'required'),
            array('time, person', 'numerical', 'integerOnly'=>true),
            array('id_recipe_category', 'length', 'max'=>11),
            array('title, image', 'length', 'max'=>255),
            array('x_image', 'file', 'types'=>'jpeg, jpg, png', 'allowEmpty'=>true, 'on'=>'update'),
            array('notice, content', 'safe'),
            // Правило, использующиеся в search
            // @todo удалить лишние атрибуты
            array('id_recipe, id_recipe_category, title, image, time, person, notice, content', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array Правила связей между моделями
     */
    public function relations()
    {
        return array(
            'idRecipeCategory' => array(self::BELONGS_TO, 'ModRecipeCategory', 'id_recipe_category'),
            'modRecipeImages' => array(self::HAS_MANY, 'ModRecipeImage', 'id_recipe'),
            'modRecipeM2mIngredients' => array(self::HAS_MANY, 'ModRecipeM2mIngredient', 'id_recipe'),
            'modRecipeSteps' => array(self::HAS_MANY, 'ModRecipeStep', 'id_recipe'),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_recipe' => 'Id Recipe',
            'id_recipe_category' => Yii::t('recipe', 'Recipe Category'),
            'title' => Yii::t('recipe', 'Recipe title '),
            'image' => Yii::t('recipe', 'Recipe image'),
            'time' => Yii::t('recipe', 'Recipe time'),
            'person' => Yii::t('recipe', 'Recipe person'),
            'notice' => Yii::t('recipe', 'Recipe notice'),
            'content' => Yii::t('recipe', 'Recipe content')
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

        $criteria->compare('id_recipe', $this->id_recipe,true);
        $criteria->compare('id_recipe_category', $this->id_recipe_category,true);
        $criteria->compare('title', $this->title,true);
        $criteria->compare('image', $this->image,true);
        $criteria->compare('time', $this->time);
        $criteria->compare('person', $this->person);
        $criteria->compare('notice', $this->notice,true);
        $criteria->compare('content', $this->content,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Получить статическую модуль определенную AR классом.
     *
     * @param string $className имя класса AR.
     * @return Recipe статическая модель класса
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
