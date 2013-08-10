<?php

Yii::import('zii.widgets.jui.CJuiInputWidget');

class DatetimePickerWidget extends CJuiInputWidget
{
    public $model = null;
    public $attribute = null;
    public $value = null;
    public $dataFormat = "yyyy-MM-dd hh:mm";
    public $options = array();
    public $htmlOptions = array();

    public function init()
    {
        $assets = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets');

        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($assets . '/css/bootstrap-datetimepicker.min.css');
        $cs->registerScriptFile($assets . '/js/bootstrap-datetimepicker.min.js');

    }

    public function run()
    {
        list($name,$id) = $this->resolveNameID();

        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;

        if(isset($this->htmlOptions['name']))
            $name=$this->htmlOptions['name'];

        $value = $this->value;

/*
        if ($this->hasModel())
        {
            echo CHtml::activeHiddenField($this->model,$this->attribute,$this->htmlOptions);
            $attribute=$this->attribute;
            $this->options['defaultDate']=$this->model->$attribute;
        }
        else
        {
            echo CHtml::hiddenField($name,$this->value,$this->htmlOptions);
            $this->options['defaultDate']=$this->value;
        }
*/

        $options = CJavaScript::encode($this->options);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('bootstrap');
        $cs->registerScript('DatetimePicker-'.$id, "$('#datepicker-".$id."').datetimepicker(".$options.");");

        $i      = CHtml::tag('i', array('class'=> 'icon-calendar', 'data-time-icon'=>'icon-time', 'data-date-icon'=>'icon-calendar'), null, true);
        $span   = CHtml::tag('span', array('class'=>'add-on'), $i, true);
        $input  = CHtml::textField($name, $value, array('id'=>$id, 'data-format'=>$this->dataFormat));
        $div    = CHtml::tag('div', array('id' => 'datepicker-'.$id,'class'=>'input-append'), $input.$span, true);

        echo $div;
    }
}