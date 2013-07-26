<?php
/**
 * Model of table "db_user".
 *
 * Column list:
 *
 * @property string $id_user
 * @property string $login
 * @property string $password
 * @property string $role
 * @property string $email
 * @property string $time_created
 * @property string $time_visited
 * @property integer $status
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class User extends CActiveRecord
{
	/**
	 * Status list
	 */
	const STATUS_NOACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_BANNED = -1;

	/**
	 * Verify passpord
	 */
	public $verifyPassword;

	/**
	 * Values list
	 *
	 * @param type $setting
	 * @param type $value
	 * @return array
	 */
	public static function values($setting = null, $value = null)
	{
		if (empty($setting))
			return;

		$settings = array(
			'role' => array(
				WebUser::ROLE_ADMIN => Yii::t('site', 'Administrator'),
				WebUser::ROLE_MODERATOR => Yii::t('site', 'Moderator'),
				WebUser::ROLE_GUEST => Yii::t('site', 'Guest'),
			),
			'status' => array(
				self::STATUS_BANNED => Yii::t('site', 'User banned'),
				self::STATUS_ACTIVE => Yii::t('site', 'User active'),
				self::STATUS_NOACTIVE => Yii::t('site', 'User noactive'),
			),
			'sex' => array(
				self::SEX_UNDEFINED => Yii::t('site', 'Sex undefined'),
				self::SEX_MALE => Yii::t('site', 'Sex male'),
				self::SEX_FEMALE => Yii::t('site', 'Sex female'),
			)
		);

		if ($value == null && isset($settings[$setting]))
			return $settings[$setting];
		if ($value != null && isset($settings[$setting][$value]))
			return $settings[$setting][$value];
		else
			return;
	}

	/**
	 * return @type Scopes
	 */
	public function scopes()
	{
		return array(
			'active' => array(
				'condition' => 'status=' . self::STATUS_ACTIVE,
			),
			'notactive' => array(
				'condition' => 'tstatus=' . self::STATUS_NOACTIVE,
			),
			'banned' => array(
				'condition' => 't.status=' . self::STATUS_BANNED,
			),
		);
	}

	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return 'db_user';
	}

	/**
	 * @return array Rules
	 */
	public function rules()
	{
		return array(
			array('login, password, role, email', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('login, email', 'length', 'max'=>255),
			array('password, role', 'length', 'max'=>64),
			array('time_created, time_visited', 'length', 'max'=>10),
			array('id_user, login, password, role, email, time_created, time_visited, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array Relations
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array Метки атрибутов (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_user' => 'Id User',
			'login' => 'Login',
			'password' => 'Password',
			'role' => 'Role',
			'email' => 'Email',
			'time_created' => 'Time Created',
			'time_visited' => 'Time Visited',
			'status' => 'Status',
		);
	}

	/**
	 * @return CActiveDataProvider Data provider
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id_user', $this->id_user,true);
		$criteria->compare('login', $this->login,true);
		$criteria->compare('password', $this->password,true);
		$criteria->compare('role', $this->role,true);
		$criteria->compare('email', $this->email,true);
		$criteria->compare('time_created', $this->time_created,true);
		$criteria->compare('time_visited', $this->time_visited,true);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Получить статическую модуль определенную AR классом.
	 *
	 * @param string $className имя класса AR.
	 * @return User статическая модель класса
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
