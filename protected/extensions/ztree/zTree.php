<?php
/**
 * zTree class file.
 *
 * ztree Js��չ��
 * @author jake <jake451@163.com>
 * @link http://hi.baidu.com/jake451
 * @version 1.0
 */
Yii::import('zii.widgets.jui.CJuiWidget');
/**
 *
 * ztree���β˵�
 * 
 * ztree��չ��ʹ�÷���:
 * <pre>
 * $this->widget('path.ztree.zTree',array(
 * 		'treeNodeNameKey'=>'name',
 * 		'treeNodeKey'=>'id',
 * 		'treeNodeParentKey'=>'pId',
 * 		'options'=>array(
 * 			'expandSpeed'=>"",
 * 			'showLine'=>true,
 * 			),
 *		'data'=>array(
 * 			array('id'=>1, 'pId'=>0, 'name'=>'Ŀ¼1'),
 * 			array('id'=>2, 'pId'=>1, 'name'=>'Ŀ¼2'),
 * 			array('id'=>3, 'pId'=>1, 'name'=>'Ŀ¼3'),
 * 			array('id'=>4, 'pId'=>1, 'name'=>'Ŀ¼4'),
 * 			array('id'=>5, 'pId'=>2, 'name'=>'Ŀ¼5'),
 * 			array('id'=>6, 'pId'=>3, 'name'=>'Ŀ¼6')
 * 		)
 * ));
 * </pre>
 *
 * һ���������ݵ����ַ�ʽ��
 * 1������model���Ժ�(model��������model����)��
 * 		���ݻ�÷�ʽ��Ϊ$model->model()->findAll($this->criteria)
 * 		���磺
 * 			1��array(
 * 				'model'=>'tree'
 * 			)
 * 			2��array(
 * 				'model'=>$model, //�˴�Ϊһ��model����(��Ҫ��CModel������)
 * 			)
 * 2������data����
 * 		���ݿ���Ϊ���飬����model�����ݼ�(������ʽ)
 * 		���磺
 * 			1��array(
 *				'data'=>array(
 * 					array('id'=>1, 'pId'=>0, 'name'=>'Ŀ¼1'),
 * 					array('id'=>2, 'pId'=>1, 'name'=>'Ŀ¼2'),
 * 					array('id'=>3, 'pId'=>1, 'name'=>'Ŀ¼3'),
 * 					array('id'=>4, 'pId'=>1, 'name'=>'Ŀ¼4'),
 * 					array('id'=>5, 'pId'=>2, 'name'=>'Ŀ¼5'),
 * 					array('id'=>6, 'pId'=>3, 'name'=>'Ŀ¼6')
 * 				)
 * 			)
 * 
 * 			2��array(
 * 				'data'=>tree::model()->findAll()
 * 			)
 * 	�������ѣ�
 * 			1��iconsCss�������°����Ѿ�����������
 *			2��width���Բ���Ļ�������������containerId���һ��
 *
 */
class zTree extends CJuiWidget
{
	/**
	 * �ű��ļ��б�
	 * 
	 * @var array|string
	 */
	public $scriptFile=array('jquery.ztree.all-3.5.min.js');
	/**
	 * ��ʽ�ļ��б�
	 * 
	 * @var array|string
	 */
	public $cssFile=array('zTreeStyle.css');
	/**
	 * ����
	 * 
	 * @var array|string
	 */
	public $data='{}';
	/**
	 * �������
	 * 
	 * @var int
	 */
	public $width;
	/**
	 * �����߶�
	 * 
	 * @var int
	 */
	public $height;
	/**
	 * �Ƿ�ֻ����ѡ������
	 * 
	 * @var bool
	 */
	public $onlySon=false;
	/**
	 * ����������ID��
	 * 
	 * @var string
	 */
	public $backgroundId;
	/**
	 * ��������
	 * Ĭ��ΪDIV��Ϊ����û�б�����
	 * @var string
	 */
	public $backgroundTagName='div';
	/**
	 * ��������HTMLѡ��
	 * 
	 * @var array
	 */
	public $backgroundHtmlOptions=array();
	/**
	 * assetsĿ¼��ַ
	 * 
	 * @var string
	 */
	public $baseUrl;
	/**
	 * zTree���ݵ�model����
	 * ���ô����Ϊ$model::model()->findAll($this->criteria)
	 * @var string|object
	 */
	public $model;
	/**
	 * ��ѯ����
	 * ����model���Ժ���Ч
	 * @var mixed
	 */
	public $criteria;
	/**
	 * ���νڵ���������
	 * Ĭ��Ϊname
	 * @var string
	 */
	public $treeNodeNameKey='name';
	/**
	 * ���νڵ�ID����
	 * 
	 * @var string
	 */
	public $treeNodeKey;
	/**
	 * ���νڵ㸸ID����
	 * 
	 * @var string
	 */
	public $treeNodeParentKey;
	/**
	 * �Ƿ�Ϊ��ͨ����
	 * 
	 * @var bool
	 */
	public $isSimpleData=true;
	
	/**
	 * ��ʼ��
	 * @see CJuiWidget::init()
	 */
	public function init()
	{
        $path=dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
        $this->baseUrl=Yii::app()->getAssetManager()->publish($path);
        $this->themeUrl	= $this->scriptUrl	= $this->baseUrl;
        parent::init();
        
		$this->htmlOptions['id']=$this->id;
		if (!empty($this->htmlOptions['class']))
			$this->htmlOptions['class'] .= ' ztree';
		else
			$this->htmlOptions['class'] = ' ztree';

		if (!isset($this->options['data']))
		{
			$this->options['data']	= array();
		}
	
		if (!isset($this->options['data']['simpleData']))
		{
			$this->options['data']['simpleData']	= array();
		}
	
		if ($this->isSimpleData)
		{
			
			$this->options['data']['simpleData']['enable']	= true;
		}
		
		if ($this->treeNodeKey !== null)
		{
			$this->options['data']['simpleData']['idKey']		= $this->treeNodeKey;
		}
		
		if ($this->treeNodeParentKey !== null)
		{
			$this->options['data']['simpleData']['pIdKey']		= $this->treeNodeParentKey;
		}
		
		if ($this->width !== null)
		{
			$this->backgroundHtmlOptions['style'] .= " width:{$this->width}px;";
		}
		if ($this->height !== null)
		{
			$this->backgroundHtmlOptions['style'] .= " height:{$this->height}px;";
		}
		if ($this->backgroundId['id'] === null)
		{
			$this->backgroundId	= isset($this->backgroundHtmlOptions['id']) ? $this->backgroundHtmlOptions['id'] :  $this->id.'background';
		}
		$this->backgroundHtmlOptions['id']	= $this->backgroundId;
	}
	
	public function run()
	{
		//�ⲿ����
		if (!empty($this->backgroundTagName))
		{
			echo CHtml::openTag($this->backgroundTagName, $this->backgroundHtmlOptions);
		}
		//��������
		echo CHtml::tag('ul', $this->htmlOptions);
		if (!empty($this->backgroundTagName))
		{
			echo CHtml::closeTag($this->backgroundTagName);
		}
		
		// �Ƿ�ֻ����ѡ���ӽڵ�
		if ($this->onlySon)
		{
			$this->options['callback']['beforeClick']= "js:function(treeId, treeNode) {
				var check = (treeNode && !treeNode.isParent);
				return check;
			}";
		}
		
		//ע��JS
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id, implode("\n", $this->getRegisterScripts()));
	}
	
	/**
	 * ע��JS�б�
	 * 
	 */
	protected function getRegisterScripts()
	{
		$js		= array();
		$data	= $this->getData();
		$options=CJavaScript::encode($this->options);
		$js[] = "zTree_{$this->id} = $.fn.zTree.init($('#{$this->id}'), {$options}, {$data});";
		return $js;
	}
	
	
	/**
	 * �������
	 * 
	 */
	protected function getData()
	{
		if ($this->model !== null)
		{
			$model	= is_object($this->model) ? $this->model : new $this->model;
			if ($model instanceof CModel)
			{
				$data	= $model->findAll($this->criteria);
			}
		}
		else 
		{
			$data	= $this->data;
		}
		
		if(is_array($data))
		{
			$arr	= array();
			foreach ($data as $key => $value)
			{
				if ($value instanceof CModel)
				{
					$value			= $value->getAttributes();
				}
				$value['name']	= $value[$this->treeNodeNameKey];
				$arr[]	= $value;
			}
			$data	= $arr;
			$data	= CJavaScript::encode($data);
		}
		
		return $data;
	}
}