<?php
    $this->title = $model->title;

    if (!empty($model->meta_title))
        $this->pageTitle = $model->meta_title;

    if (!empty($model->meta_description))
        $this->pageDescription = $model->meta_description;

    if (!empty($model->meta_keywords))
        $this->pageKeywords = $model->meta_keywords;
?>
<div><?php echo $model->content;?></div>