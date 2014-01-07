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
 * @property string $time_updated
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
     * Date list
     */
    public $date_created;
    public $date_updated;
    public $date_visited;

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
            return false;

        $settings = array(
            'role' => array(
                WebUser::ROLE_ADMIN => Yii::t('site', 'Administrator'),
                WebUser::ROLE_MODERATOR => Yii::t('site', 'Moderator'),
                WebUser::ROLE_MANAGER => Yii::t('site', 'Manager'),
                WebUser::ROLE_USER => Yii::t('site', 'User'),
                WebUser::ROLE_GUEST => Yii::t('site', 'Guest'),
            ),
            'status' => array(
                self::STATUS_BANNED => Yii::t('site', 'User banned'),
                self::STATUS_ACTIVE => Yii::t('site', 'User active'),
                self::STATUS_NOACTIVE => Yii::t('site', 'User noactive'),
            )
        );

        if ($value == null && isset($settings[$setting]))
            return $settings[$setting];
        else if ($value != null && isset($settings[$setting][$value]))
            return $settings[$setting][$value];
        else
            return false;
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
                'condition' => 't.status=' . self::STATUS_NOACTIVE,
            ),
            'banned' => array(
                'condition' => 't.status=' . self::STATUS_BANNED,
            )
        );
    }

    /**
     * @return string Table name
     */
    public function tableName()
    {
        return '{{db_user}}';
    }

    /**
     *
     */
    public function behaviors()
    {
        return array(
            'withRelated' => array(
                'class' => 'WithRelatedBehavior',
            ),
        );
    }

    /**
     * @return array Rules
     */
    public function rules()
    {
        return array(
            array('login, password, email, role', 'required'),
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
            'profile' => array(self::HAS_ONE, 'Profile', 'id_user'),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_user' => 'Id User',
            'login' => Yii::t('site', 'Login'),
            'password' => Yii::t('site', 'Password'),
            'oldPassword' => Yii::t('site', 'Old password'),
            'verifyPassword' => Yii::t('site', 'Verify password'),
            'role' => Yii::t('site', 'Role'),
            'email' => Yii::t('site', 'Email'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'time_visited' => Yii::t('site', 'Time visited'),
            'status' => Yii::t('site', 'Status'),
        );
    }

    /**
     * @return CActiveDataProvider Data provider
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id_user', $this->id_user, true);
        $criteria->compare('login', $this->login, true);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_visited', $this->time_visited, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria
        ));
    }

    /**
     * Добавить значения дат
     */
    protected function afterFind()
    {
        $this->date_created = !empty($this->time_created)?date('d-m-Y H:i', $this->time_created):null;
        $this->date_updated = !empty($this->time_updated)?date('d-m-Y H:i', $this->time_updated):null;
        $this->date_visited = !empty($this->date_visited)?date('d-m-Y H:i', $this->time_visited):null;

        parent::afterFind();
    }

    /**
     * Get model
     *
     * @param string $className имя класса AR.
     * @return User static class model
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Получить список пользователей
     */
    public static function getDropDownData($role = null, $fields = array())
    {
        $list = self::model()->active()->with('profile')->findAll(array(
            'condition' => 't.role = :role',
            'params'=>array(
                ':role'=>WebUser::ROLE_MANAGER
            )
        ));

        $users = array();
        foreach($list as $user){
            $username = null;
            foreach ($fields as $field)
                $username .= $user->profile->$field." ";
            if (empty($username))
                $username = $user->login;
            $users[$user->id_user] = $username;
        }

        return $users;
    }

}
