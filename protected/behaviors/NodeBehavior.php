<?php

class NodeBehavior extends CBehavior
{
    const LEVEL_LIMIT = 10;

    /**
     * Текущий узел
     */
    protected $_nodeCurrent = null;
    
    /**
     * Цепочка узлов до текущего
     */
    protected $_nodeChain = array();

    /**
     * Список узлов
     */
    protected $_nodeList = null;

    /**
     * Список всех путей узлов
     */
    protected $_nodePaths = null;

    /**
     * Маршрутизаторы модулей
     */
    protected $_moduleUrlManager = array();

    /**
     * Экземпляр маршрутизатора без правил маршрутизации
     */
    protected $_instanceUrlManager;

    /**
     * Устанавливаем текущий узел
     */
    public function setNode($node){
        if ($this->_nodeCurrent == null)
            $this->_nodeCurrent = $node;
    }

    /**
     * Устанавливаем текущую цепочку узлов
     */
    public function setNodeChain($nodeChain){
        if ($this->_nodeChain == null)
            $this->_nodeChain = $nodeChain;
    }

    /**
     * Получить ID текущего узла
     */
    public function getNodeId()
    {
        if ($this->_nodeCurrent)
            return $this->_nodeCurrent->id_node;
    }

    /**
     * Получить путь текущего узла
     */
    public function getNodePath()
    {
        if ($this->_nodeCurrent)
            return $this->_nodeCurrent->path;
    }

    /**
     * Получить атрибуты текущего узла
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
     * Получить путь к узлу по id узла
     */
    public function getNodeByID($id_node = null)
    {
        $this->_getNodeList();

        if (isset($this->_nodeList[$id_node])){
            return $this->_nodeList[$id_node];
        }
    }

    /**
     * Получить путь к узлу по id узла
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
     * Получить список узлов из БД
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
     * Получить цепочку узлов относительно текущего
     */
    public function getNodeChain()
    {
        return $this->_nodeChain;
    }

    /** 
     * Сформировать массив путей
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
     * Получить url-менеджер модуля
     */
    public function getModuleUrlManager(&$node)
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
     * Получаем UrlManager с настройками
     */
    public function _getUrlManager(){
    
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

    /**
     * Создать ссылку для текущего узла
     */
    public function createNodeUrl($id_node, $route, $params=array(), $ampersand='&')
    {
        $node = $this->getNodeByID($id_node);

        if (empty($node->id_node)){
            if (YII_DEBUG)
                throw new CException(Yii::t('yii','Node ID #{id_node} not exists.', array('{id_node}' => $id_node)));
            else
                throw new CHttpException(404, Yii::t('site', 'The requested page does not exist.'));
        }

        $moduleName = $node->module;

        if (!isset($this->_moduleUrlManager[$id_node]) && Yii::app()->hasModule($moduleName)){
            $moduleRoute = array();
            $rules = Yii::app()->getModule($moduleName)->route();
            foreach($rules as $key => $route)
                $moduleRoute[ltrim($node->path,"/").$key] = $route;

            $manager = $this->_getUrlManager();
            $manager->rules = null;
            $manager->addRules($moduleRoute);
            $this->_moduleUrlManager[$id_node] = $manager;
        }
        $manager = $this->_moduleUrlManager[$id_node];

        return $manager->createUrl($route, $params, $ampersand);
    }
}

?>