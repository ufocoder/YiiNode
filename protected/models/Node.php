<?php
/**
 * Model of table "db_node".
 *
 * Column list:
 *
 * @property string $id_node
 * @property string $id_node_parent
 * @property integer $position
 * @property string $rgt
 * @property string $lft
 * @property integer $level
 * @property string $path
 * @property string $module
 * @property string $title
 * @property string $image
 * @property string $content
 * @property integer $time_created
 * @property integer $time_updated
 *
 * Relation list:
 *
 * @property Node $Parent
 * @property Node[] $Child
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class Node extends CActiveRecord
{
    public $node_position;
    public $node_related;

    /**
     * Position list
     */
    const POSITION_PREV = 1;
    const POSITION_NEXT = 2;
    const POSITION_CHILD = 3;

    /**
     *
     */
    protected $has_root = null;
     
    /**
     * @return string get the table name
     */
    public function tableName()
    {
        return '{{db_node}}';
    }

    /**
     * @return type
     */
    public function values($setting = null, $value = null)
    {
        $settings = array(
            "position" => array(
                self::POSITION_PREV => Yii::t('site','Node previous'),
                self::POSITION_NEXT => Yii::t('site','Node after'),
                self::POSITION_CHILD => Yii::t('site','Node as child'),
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
        elseif (isset($settings[$setting]))
            return $settings[$setting];
    }

    /**
     * @return array Validation rule list
     */
    public function rules()
    {
        $count = self::model()->count();
        $this->has_root = ($count == 0) ? false : true;

        $rules = array(
            array('module', 'required', 'on'=>'create'),
            array('enabled', 'boolean', 'allowEmpty'=>true),
            array('title, slug', 'required'),
            array('position, level, time_created, time_updated', 'numerical', 'integerOnly'=>true),
            array('id_node_parent, rgt, lft', 'length', 'max'=>11),
            array('path, module, title, image', 'length', 'max'=>255),
            array('content', 'default', 'value' => null),
            // Правило, использующиеся в search
            array('id_node, id_node_parent, position, rgt, lft, level, path, module, title, time_created, time_updated', 'safe', 'on'=>'search'),
        );

        if ($this->has_root){
            $rules = array_merge($rules, array(
                array('node_position', 'in', 'range'=> array_keys($this->values('position')), 'allowEmpty'=>true, 'on'=>'create'),
                array('node_related', 'numerical', 'integerOnly'=>true, 'on'=>'create'),
                array('node_position, node_related', 'required', 'on'=>'create'),
            ));
        }

        return $rules;
    }

    /**
     * @return array Relation list
     */
    public function relations()
    {
        return array(
            'Parent' => array(self::BELONGS_TO, 'Node', 'id_node_parent'),
            'Child' => array(self::HAS_MANY, 'Node', 'id_node_parent'),
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
            'route' => array(
                'select' => 'id_node, title, path, module',
                'order' => 't.path DESC'
            ),
            'tree' => array(
                'select' => "*, CONCAT(REPEAT('—', level-1), IF(level > 0,' ',''),`title`) as `title`"
            )
        );
    }

    /**
     * @return array Behavior list
     */
    public function behaviors()
    {
        return array(
            'NestedSet' => array(
                'class' => 'application.behaviors.NestedSetBehavior',
                'rootAttribute' => 'root',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level'
            )
        );
    }

    /**
     * @return array Attribute label list (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_node' => Yii::t('site', 'Id node'),
            'id_node_parent' => Yii::t('site', 'Parent node'),
            'node_position' => Yii::t('site', 'Node position'),
            'node_related' => Yii::t('site', 'Node related'),
            'rgt' => Yii::t('site', 'Rigth child'),
            'lft' => Yii::t('site', 'Left child'),
            'level' => Yii::t('site', 'Level'),
            'path' => Yii::t('site', 'Path'),
            'module' => Yii::t('site', 'Module'),
            'title' => Yii::t('site', 'Title'),
            'image' => Yii::t('site', 'Image'),
            'content' => Yii::t('site', 'Description'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
        );
    }

    /**
     * @return CActiveDataProvider data provider
     */
    public function search()
    {
        // @todo удалить лишние атрибуты

        $criteria=new CDbCriteria;

        $criteria->compare('id_node', $this->id_node,true);
        $criteria->compare('id_node_parent', $this->id_node_parent,true);
        $criteria->compare('position', $this->position);
        $criteria->compare('rgt', $this->rgt,true);
        $criteria->compare('lft', $this->lft,true);
        $criteria->compare('level', $this->level);
        $criteria->compare('path', $this->path,true);
        $criteria->compare('module', $this->module,true);
        $criteria->compare('title', $this->title,true);
        $criteria->compare('image', $this->image,true);
        $criteria->compare('content', $this->content,true);
        $criteria->compare('time_created', $this->time_created);
        $criteria->compare('time_updated', $this->time_updated);

        $criteria->scopes = 'tree';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Получить статическую модуль определенную AR классом.
     *
     * @param string $className имя класса AR.
     * @return Node статическая модель класса
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    
    public function moveNode()
    {
        if (!isset($this->node_position) || !isset($this->node_related))
            return;

        $node = self::model()->findByPk($this->node_related);

        if (!empty($node)){
            switch($this->node_position)
            {
                case self::POSITION_PREV:
                    if ($node->isRoot()){
                        throw new CHttpException(403, Yii::t('error', 'There is only one root node.'));
                    }
                    else{
                        if ($this->isNewRecord)
                            $this->insertBefore($node);
                        else
                            $this->moveBefore($node);
                            
                        $parentNode = self::model()->findByPk($node->id_node_parent);
                        $this->id_node_parent = $parentNode->id_node;
                    }

                    $this->setPosition($node->position, true);
                break;

                case self::POSITION_NEXT:
                    if ($node->isRoot()){
                        throw new CHttpException(403, Yii::t('error', 'There is only one root node.'));
                    }
                    else{
                        if ($this->isNewRecord)
                            $this->insertAfter($node);
                        else
                            $this->moveAfter($node);

                        $parentNode = self::model()->findByPk($node->id_node_parent);
                        $this->id_node_parent = $parentNode->id_node;
                        
                    }
                    $this->setPosition($node->position, false);
                break;

                case self::POSITION_CHILD:
                    $criteria=new \CDbCriteria;
                    $criteria->select='max(position) AS position';
                    $criteria->addCondition('id_node_parent = :id_parent');
                    $criteria->params = array(':id_parent' => $node->id_node);
                    $row = self::model()->find($criteria);

                    if ($this->isNewRecord)
                        $this->appendTo($node);
                    else
                        $this->moveAsLast($node);

                    $this->id_node_parent = $node->id_node;
                    $this->setPosition($row['position'], false);
                break;
            }
        }
    }

    protected function setPosition($position, $before = true)
    {
        $position = intval($position);

        $where = (!empty($this->id_parent))?'= '.$this->id_parent:"is NULL";

        $up     = 'UPDATE '.$this->tableName().' SET position=position+1 WHERE position >= '.$position.' AND id_node_parent '.$where;
        $down   = 'UPDATE '.$this->tableName().' SET position=position-1 WHERE position <= '.$position.' AND id_node_parent '.$where;

        if ($before)
            Yii::app()->db->createCommand($up)->execute(); 
        else
            Yii::app()->db->createCommand($down)->execute();

        $this->position = $position;
    }
 
    protected function updatePosition(){

        $criteria=new \CDbCriteria;
        $criteria->order = 'position ASC';
        
        if ($this->id_parent){
            $criteria->addCondition('id_parent = :id_parent');
            $criteria->params = array(':id_parent' => $this->id_parent);
        }else{
            $criteria->addCondition('id_parent is NULL');
        }

        $list = self::model()->findAll($criteria);
        $i=0;
        foreach($list as $item){
            $item->position = $i;
            $item->saveNode();
            $i++;
        }
    }

    
}
