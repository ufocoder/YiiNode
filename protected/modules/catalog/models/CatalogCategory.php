<?php

class CatalogCategory extends \CActiveRecord
{
    public $x_image;
    public $delete_image;

    public $category_position;
    public $category_related;

    /**
     * Position list
     */
    const POSITION_PREV = 1;
    const POSITION_NEXT = 2;
    const POSITION_CHILD = 3;

    protected $old_attributes = array();

    private $data_parent;
    private $data_category;

    /**
     * @return type
     */
    public function values($setting = null, $value = null)
    {
        $settings = array(
            "position" => array(
                self::POSITION_PREV => Yii::t('site','Category previous'),
                self::POSITION_NEXT => Yii::t('site','Category after'),
                self::POSITION_CHILD => Yii::t('site','Category as child'),
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
        elseif (isset($settings[$setting]))
            return $settings[$setting];
    }

    public function published(){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.enabled = 1'
        ));
        return $this;
    }

    public function behaviors()
    {
        return array(
            'NestedSet' => array(
                'class' => 'application.behaviors.NestedSetBehavior',
                'hasManyRoots'  => true,
                'rootAttribute' => 'root',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level'
            )
        );
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_catalog_category}}';
    }

    /**
     * @return array Validation rule list
     */
    public function rules()
    {
        $rules = array(
            // default
            array('position, level time_created, time_updated', 'numerical', 'integerOnly'=>true),
            array('slug', 'required'),
            array('slug', 'match', 'pattern' => '/^\w+[\_\-\.\w]+$/i'),
            // create & update
            array('enabled', 'boolean', 'allowEmpty'=>true, 'on'=>'create,update'),
            array('title', 'required', 'on'=>'create,update'),
            array('id_category_parent, rgt, lft', 'length', 'max'=>11, 'on'=>'create,update'),
            array('path, title, image', 'length', 'max'=>255, 'on'=>'create,update'),
            array('notice', 'default', 'value' => null, 'on'=>'create,update'),
            array('category_position', 'in', 'range'=> array_keys($this->values('position', 'data')), 'allowEmpty'=>true, 'on'=>'create,update'),
            array('category_related', 'numerical', 'integerOnly'=>true, 'on'=>'create, move'),
            // search
            array('id_category, id_category_parent, position, rgt, lft, level, path, module, title, time_created, time_updated', 'safe', 'on'=>'search'),
        );

        return $rules;
    }

    /**
     * @return array Relation list
     */
    public function relations()
    {
        return array(
            'Parent' => array(self::BELONGS_TO, 'CatalogCategory', 'id_category_parent'),
            'Child' => array(self::HAS_MANY, 'CatalogCategory', 'id_category_parent'),
            'products' => array(self::HAS_MANY, 'CatalogProduct', 'id_category'),
        );
    }

    /**
     * @return array Scopes
     */
    public function scopes()
    {
        return array(
            'menu' => array(
                'condition' => 't.enabled = 1 AND t.hidden = 0',
                'order' => 't.lft'
            ),
            'active' => array(
                'condition' => 't.enabled = 1',
            ),
            'route' => array(
                'order' => 't.path DESC'
            ),
            'position' => array(
                'order' => 't.position ASC'
            ),
            'tree' => array(
                'select' => "*, CONCAT(REPEAT('—', level-1), IF(level > 0, ' ',''),`title`) as `title`",
                'order' => 't.root, t.lft'
            )
        );
    }


    public function attributeLabels()
    {
        return array(
            'id_parent' => Yii::t('site', 'Parent category'),
            'title' => Yii::t('site', 'Title'),
            'notice' => Yii::t('site', 'Notice'),
            'position' => Yii::t('site', 'Position'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'x_image' => Yii::t('site', 'Image'),
            'delete_image'=> Yii::t('site', 'Delete image'),
            'image' => Yii::t('site', 'Image'),
            'enabled' => Yii::t('site', 'Enabled'),
            'category_position' => Yii::t('site', 'Category position'),
            'category_related' => Yii::t('site', 'Category related'),
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id_category);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('enabled',$this->enabled,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


    /**
     * Удаляем файл после удаления модели
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            $filename = self::getUploadPath().$this->image;
            if (file_exists($filename) && !empty($this->image))
                unlink($filename);
            return true;
        }
    }

    public function saveAsNode()
    {
        if (!isset($this->category_position) || !isset($this->category_related))
            return $this->saveNode();

        $node = self::model()->findByPk($this->category_related);
        $flagSave = false;

        if (!empty($node))
            switch($this->category_position)
            {
                case self::POSITION_PREV:
                    // Nested Set
                    if ($node->isRoot()){
                        if ($this->isNewRecord){
                            $flagSave = true;
                            $flagReturn = $this->saveNode();
                        }
                        else
                            $this->moveAsRoot($node);
                    }
                    else
                        if ($this->isNewRecord)
                            $this->insertBefore($node);
                        else
                            $this->moveBefore($node);

                    // Adjacency List
                    $parentNode = self::model()->findByPk($node->id_category_parent);
                    if ($parentNode){
                        $this->id_category_parent = $parentNode->id_category;
                        $this->path = rtrim($parentNode->path, "/")."/".$this->slug;
                    }
                    else{
                        $this->id_category_parent = null;
                        $this->path = "/".$this->slug;
                    }
                    $this->setPosition($node->position, true);

                    // save node
                    return $this->saveNode();
                break;

                case self::POSITION_NEXT:
                    // Nested Set
                    if ($node->isRoot()){
                        if ($this->isNewRecord){
                            $flagSave = true;
                            $flagReturn = $this->saveNode();
                        }
                        else
                            $this->moveAsRoot($node);
                    }
                    else
                        if ($this->isNewRecord)
                            $this->insertAfter($node);
                        else
                            $this->moveAfter($node);

                    // Adjacency List
                    $parentNode = self::model()->findByPk($node->id_category_parent);
                    if ($parentNode){
                        $this->id_category_parent = $parentNode->id_category;
                        $this->path = rtrim($parentNode->path, "/")."/".$this->slug;
                    }
                    else{
                        $this->id_category_parent = null;
                        $this->path = "/".$this->slug;
                    }
                    $this->setPosition($node->position, false);

                    // save node
                    return $this->saveNode();
                break;

                case self::POSITION_CHILD:
                    // Nested Set
                    if ($this->isNewRecord)
                        $this->appendTo($node);
                    else
                        $this->moveAsLast($node);

                    // Adjacency List
                    $criteria = new CDbCriteria;
                    $criteria->select='max(position) AS position';
                    $criteria->addCondition('id_category_parent = :id_parent');
                    $criteria->params = array(':id_parent' => $node->id_category);
                    $row = self::model()->find($criteria);

                    $this->id_category_parent = $node->id_category;
                    $this->path = rtrim($node->path, "/")."/".$this->slug;
                    $this->setPosition(isset($row['position'])?$row['position']:0, false);

                    // save node
                    return $this->saveNode();

                break;
            }
    }

///
    protected function setPosition($position, $before = true)
    {
        $position = intval($position);

        $where = (!empty($this->id_category_parent))?' = '.$this->id_category_parent:" is NULL";

        $up     = 'UPDATE '.$this->tableName().' SET position=position+1 WHERE position >= '.$position.' AND id_category_parent'.$where;
        $down   = 'UPDATE '.$this->tableName().' SET position=position-1 WHERE position <= '.$position.' AND id_category_parent'.$where;

        if ($before)
            Yii::app()->db->createCommand($up)->execute();
        else
            Yii::app()->db->createCommand($down)->execute();

        $this->saveAttributes(array('position' => $position));

        $this->updatePosition();
    }

    public function updatePosition()
    {
        $criteria=new CDbCriteria;
        $criteria->order = 'position ASC';

        if ($this->id_category_parent){
            $criteria->addCondition('id_category_parent = :id_parent');
            $criteria->params = array(':id_parent' => $this->id_category_parent);
        }else{
            $criteria->addCondition('id_category_parent is NULL');
        }

        $list = self::model()->findAll($criteria);

        $i=0;
        foreach($list as $item){

            $item->saveAttributes(array('position'=>$i));
            if ($this->id_category == $item->id_category)
                $this->saveAttributes(array('position'=>$i));
            $i++;
        }
    }

    public function updatePaths()
    {
        if (!$this->isRoot()){
            $this->saveAttributes(array('path'=>$this->Parent->path.$this->slug));

            $condition  = $this->descendants()->getDbCriteria()->condition;
            $value      = "CONCAT('".$this->path."', SUBSTRING(path, LENGTH('".$this->old_attributes['path']."') +1))";
            $command    = "UPDATE `".$this->tableName()."` `".$this->getTableAlias()."` SET `path` = ".$value." WHERE ".$condition;

            return Yii::app()->db->createCommand($command)->execute();
        }else
            return true;

    }

    protected function afterFind()
    {
        $this->old_attributes = $this->attributes;
        parent::afterFind();
    }


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


    public function treechild($id)
    {
        $curnode = self::model()->findByPk($id);
        if ($curnode){
            $childrens = $curnode->children()->findAll();
            if(sizeOf($childrens)>0){
                $out = array();
                foreach($childrens as $children)
                {
                    $currow = array(
                        'id' => $children->id_category,
                        'text' => $children->title,
                        'children' => $this->treechild($children->id_category)
                    );
                    $out[]=$currow;
                }
            return $out;
            }
            else{
                return null;
            }
        }
        return null;
    }

    public function buildNestedSet()
    {
        $list = \Yii::app()->db->createCommand("SELECT * FROM ".$this->tableName()." ORDER BY `id_category_parent` , `position`")->queryAll();

        $roots = array();
        $parent = array();
        foreach($list as $item)
        {
            if (empty($item['id_parent']))
                $roots[] = $item['id'];
            else
                $this->data_parent[$item['id_parent']][] = $item['id'];

            $this->data_category[$item['id']] = $item['id'];
        }

        foreach($roots as $root)
        {
            $this->data_parent[0] = array($root);
            $this->shapeShift($root, 0, 1);
        }

        return true;
    }

    protected function shapeShift($rootId, $parentId, $lkey, $level = 1, $position = 0)
    {
        if (isset($this->data_parent[$parentId]))
        {
            foreach ($this->data_parent[$parentId] as $id)
            {
                $rkey = $this->shapeShift($rootId, $id, $lkey + 1, $level + 1);
                $position++;
                \Yii::app()->db->createCommand()->update($this->tableName(), array(
                    "lft"   => $lkey,
                    "rgt"   => $rkey,
                    "level" => $level,
                    "root"  => $rootId,
                    "position" => $position
                ), "id=:id",  array(':id'=>$id));
                $lkey = $rkey + 1;
            }
        }
        return $lkey;
    }

    public function updateProductCount()
    {
        $data_tree = array();
        $data_count = array();
        $data_item = array();
        $trees = array();

        $query_tree = self::model()->findAll("enabled = 1");

        foreach($query_tree as $tree)
        {
            $data_count[$tree['id']] = 0;
            if (empty($tree['id_parent']))
                $data_tree[0][] = $tree['id'];
            else
                $data_tree[$tree['id_parent']][] = $tree['id'];
        }

        $categoryPk = 'id_category';
        $productPk  = 'id_product';

        $command = Yii::app()->db->createCommand();
        $command->select = "`".$categoryPk."`, COUNT(`".$productPk."`) as `count`";
        $command->from = CatalogProduct::model()->tableName();
        $command->where = "`enabled` = 1 AND `".$categoryPk."` IS NOT NULL";
        $command->group = "".$categoryPk."";

        $product_count = $command->queryAll();

        foreach($product_count as $count)
            $data_count[$count['id_category']] = $count['count'];

        // узнаем всех детей с учетом глубины
        $data_item = array();
        if (!empty($data_tree[0]))
            foreach($data_tree[0] as $id_parent)
            {
                $data_child = array();
                $this->__getChildByID($id_parent, $data_tree, $data_child);
                $data_item[$id_parent] = $data_child;
            }

        $command = Yii::app()->db->createCommand();

        // подсчитываем с учетом глубины
        foreach($data_item as $id_parent => $childs)
        {
            $count = $data_count[$id_parent];
            foreach($childs as $id_child){
                if (isset($data_count[$id_child]))
                {
                    $command->update($this->tableName(), array("count"=>$data_count[$id_child]), "id=:id", array(":id"=>$id_child));
                    $command->reset();
                    $count += $data_count[$id_child];
                }
            }

            if (isset($data_count[$id_parent])){
                $command->update($this->tableName(), array("count"=>$count), "id=:id", array(":id"=>$id_parent));
                $command->reset();
            }
        }

    }

    // возравщаем id узлов которые связаны по родителю
    private function __getChildByID($id, &$data_tree, &$return = array())
    {
        if (!empty($data_tree[$id]))
            foreach($data_tree[$id] as $id_tree){
                $return[] = $id_tree;
                $this->__getChildByID($id_tree, $data_tree, $return);
            }
    }

}