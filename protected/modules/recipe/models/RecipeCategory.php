<?php
/**
 * Модель таблицы "mod_recipe_category".
 *
 * Список колонок:
 *
 * @property string $id_recipe_category
 * @property string $id_parent
 * @property string $title
 *
 * Список связей:
 *
 * @property ModRecipe[] $modRecipes
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class RecipeCategory extends CActiveRecord
{
	/**
	 * @return string Получить имя таблицы связанное с моделью
	 */
	public function tableName()
	{
		return 'mod_recipe_category';
	}

	/**
	 * @return array Правила валидации атрибутов
	 */
	public function rules()
	{
		return array(
			array('id_parent, title', 'required'),
			array('id_parent', 'length', 'max'=>11),
			array('title', 'length', 'max'=>255),
			// Правило, использующиеся в search
			// @todo удалить лишние атрибуты
			array('id_recipe_category, id_parent, title', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array Правила связей между моделями
	 */
	public function relations()
	{
		return array(
			'modRecipes' => array(self::HAS_MANY, 'ModRecipe', 'id_recipe_category'),
		);
	}

	/**
	 * @return array Метки атрибутов (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_recipe_category' => 'Id Recipe Category',
			'id_parent' => 'Id Parent',
			'title' => 'Title',
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

		$criteria->compare('id_recipe_category', $this->id_recipe_category,true);
		$criteria->compare('id_parent', $this->id_parent,true);
		$criteria->compare('title', $this->title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Получить статическую модуль определенную AR классом.
	 *
	 * @param string $className имя класса AR.
	 * @return RecipeCategory статическая модель класса
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
