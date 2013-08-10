<?php
/**
 * Node behavior
 *
 * @author Sergei Ivanov <xifrin@gmail.com>
 * @copyright 2013
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class NodeBehavior extends CBehavior
{
    const LEVEL_LIMIT = 10;

    /**
     * Current node
     */
    protected $_nodeCurrent = null;

    /**
     * Chain nodes for current node
     */
    protected $_nodeChain = array();

    /**
     * Node list
     */
    protected $_nodeList = null;

    /**
     * Node path list
     */
    protected $_nodePaths = null;

    /**
     * Moduel url managers
     */
    protected $_moduleUrlManager = array();

    /**
     * UrlManager instance without rules
     */
    protected $_instanceUrlManager;

    /**
     * Admin module flag
     */
    protected $_nodeAdmin = false;

    /**
     * Set current node
     */
    public function setNode($node, $flag_admin = false)
    {
        if ($this->_nodeCurrent == null){
            $this->_nodeCurrent = $node;
            if ($flag_admin)
                $this->_nodeAdmin = true;
        }
    }

    /**
     *
     */
    public function isAdminNode()
    {
        return $this->_nodeAdmin;
    }

    /**
     * Get current node
     */
    public function getNode(){
        if ($this->_nodeCurrent != null)
            return $this->_nodeCurrent;
    }

    /**
     * Set chain nodes for current node
     */
    public function setNodeChain($nodeChain){
        if ($this->_nodeChain == null)
            $this->_nodeChain = $nodeChain;
    }

    /**
     * Get current node Id
     */
    public function getNodeId()
    {
        if ($this->_nodeCurrent)
            return $this->_nodeCurrent->id_node;
    }

    /**
     * Get current node path
     */
    public function getNodePath()
    {
        if ($this->_nodeCurrent)
            return $this->_nodeCurrent->path;
    }

    /**
     * Get current node attribute
     */
    public function getNodeAttribute($attr = null)
    {
        if ($this->_nodeCurrent){
            if (isset($this->_nodeCurrent[$attr]))
                return $this->_nodeCurrent[$attr];
            elseif ($attr == null)
                return $this->_nodeCurrent->attributes;
        }
    }

    /**
     * Get node by ID
     */
    public function getNodeByID($id_node = null)
    {
        $this->_getNodeList();

        if (isset($this->_nodeList[$id_node])){
            return $this->_nodeList[$id_node];
        }
    }

    /**
     * Get node by path
     */
    public function getNodeByPath($path_node = null)
    {
        $this->_getNodeList();

        if (isset($this->_nodePaths[$path_node])){
            $id_node = $this->_nodePaths[$path_node];
            if (isset($this->_nodeList[$id_node])){
                return $this->_nodeList[$id_node];
            }
        }
    }

    /**
     * Get node list from database
     */
    protected function _getNodeList()
    {
        if ($this->_nodeList == null && $this->_nodePaths == null){
            $nodes = Node::model()->route()->findAll();
            foreach ($nodes as $node){
                $this->_nodeList[$node->id_node] = $node;
                $this->_nodePaths[$node->path] = $node->id_node;
            }
        }
    }

    /**
     * Get node chain
     */
    public function getNodeChain()
    {
        return $this->_nodeChain;
    }

    /**
     * Get array path for string path
     */
    protected function _getNodePathList($path)
    {
        if (empty($path))
            return array("/");

        $paths = explode("/", $path);

        $paths = array_slice($paths, 0, self::LEVEL_LIMIT);
        $result = array();
        for ($i = count($paths); $i > 0; $i--)
            for ($j = 0; $j < $i; $j++)
                if (!isset($result[$i]))
                    $result[$i] = "/".$paths[$j];
                else
                    $result[$i].= "/".$paths[$j];

        $result[] = "/";

        return $result;
    }

    /**
     * Get module UrlManager
     */
    public function getModuleUrlManager($node)
    {
        if (empty($node))
            return false;

        if (!isset($this->_moduleUrlManager[$node->id_node]) && Yii::app()->hasModule($node->module)){
            $moduleRoute = array();
            $rules = Yii::app()->getModule($node->module)->route();
            foreach($rules as $key => $route)
                $moduleRoute[ltrim($node->path, "/").$key] = $route;

            $manager = $this->_getUrlManager();
            $manager->addRules($moduleRoute);
            $this->_moduleUrlManager[$node->id_node] = $manager;
        }

        if (isset($this->_moduleUrlManager[$node->id_node]))
            return $this->_moduleUrlManager[$node->id_node];
    }

    /**
     * Get UrlManager with settings
     */
    protected function _getUrlManager(){

        if ($this->_instanceUrlManager == null)
        {
            $class = get_class(Yii::app()->urlManager);
            $manager = new $class;
            $manager->setUrlFormat($manager::PATH_FORMAT);
            $params = array('useStrictParsing', 'caseSensitive', 'urlSuffix', 'showScriptName', 'appendParams');
            foreach ($params as $param)
                $manager->$param = Yii::app()->urlManager->$param;

            $this->_instanceUrlManager = $manager;
        }

        return clone $this->_instanceUrlManager;
    }

}

?>