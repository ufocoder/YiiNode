<?php

class BlockWidget extends CWidget
{
    public $title = array();
    public $type = "input";

    public function run()
    {
        if (empty($this->title))
            return;

        $command = Yii::app()->db->createCommand();
        $data = $command->select("*")
                        ->from("{{data_block}}")
                        ->where("title=:title", array(":title"=>$this->title))
                        ->queryRow();

        if (empty($data)){
            $command->reset();
            $command->insert("{{data_block}}", array(
                "title" => $this->title, 
                "type" => $this->type,
                "time_created" => time()
            ));
        }else
            echo $data["content"];
    }
}