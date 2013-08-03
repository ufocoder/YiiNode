<?php
  // @TODO: optimization and minimization
  $node   = Yii::app()->getNode();

  if (empty($node)){
      $node = Node::model()->roots()->find();
  }

  $nodeId = $node->id_node;
  $parent = $node->parent()->find();
  $items  = array();

  if ($node->isRoot()){
      $nodes = $node->children()->findAll();
      foreach ($nodes as $node)
          $items[] = array('label'=>$node->title, 'url'=>$node->path);
  }
  elseif ($parent->isRoot())
  {
      $nodes = $parent->children()->findAll();
      foreach ($nodes as $node)
          if ($node->id_node == $nodeId){
              $children = array();
              $childs = $node->children()->findAll();

              foreach ($childs as $child)
                  $children[] = array(
                      'label' => $child->title,
                      'url' => $child->path,
                  );
              $items[] = array(
                  'label' => $node->title,
                  'url' => $node->path,
                  //'active' => true,
                  'items' => $children
              );
          }
          else
          {
            $items[] = array(
                'label'=>$node->title,
                'url'=>$node->path);
          }

  } else {

      $children = array();

      $child = array();
      $nodes = $parent->children()->findAll();
      foreach ($nodes as $node)
          if ($node->id_node == $nodeId)
          {
              $grand = array();
              $grandchilds = $node->children()->findAll();
              foreach ($grandchilds as $grandchild)
                  $grand[] = array(
                      'label' => $grandchild->title,
                      'url' => $grandchild->path,
                  );

              $child[] = array(
                  'label' => $node->title,
                  'url' => $node->path,
                  'active' => true,
                  'items' => $grand
              );
          }
            else
                $child[] = array(
                  'label' => $node->title,
                  'url' => $node->path
                );

      $children = $child;

      $flag = false;

      while(!empty($parent)){
          $nodes = $parent->children()->findAll();

          if ($flag){
              $child = array();
              foreach ($nodes as $node)
                  if ($node->id_node == $nodeId)
                      $child[] = array(
                          'label' => $node->title,
                          'url' => $node->path,
                          'active' => true,
                          'items' => $children
                      );
                  else
                      $child[] = array(
                          'label' => $node->title,
                          'url' => $node->path
                      );

              $children = $child;
          }else{
              $flag = true;
          }

          $nodeId = $parent->id_node;
          $parent = $parent->parent()->find();
      }

      $items = $children;
  }

  $this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => false,
    'fixed' => false,
    'items' => array(
      array(
        'class' => 'bootstrap.widgets.TbMenu',
        'items' => $items
      )
    )
  ));
?>