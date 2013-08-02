<?php
    $baseUrl = Yii::app()->request->baseUrl;
?>
<?php $this->renderPartial('//layouts/__header'); ?>
<div class="container-narrow">

    <div class="masthead">
        <h2>YiiNode</h2>

        <?php $this->widget('zii.widgets.CMenu', array(
          'id'=>null,
          'items'=>array(
            array('label'=>Yii::t('site', 'Login'), 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
            array('label'=>Yii::t('site', 'Registration'), 'url'=>array('/user/registration'), 'visible'=>Yii::app()->user->isGuest),
            array('label'=>Yii::t('site', 'Account'), 'url'=>array('/user/profile'), 'visible'=>!Yii::app()->user->isGuest),
            array('label'=>Yii::t('site', 'Logout').' ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest)
          ),
          'htmlOptions' => array(
              'class' => 'nav nav-pills pull-right',
          )
        ));
      ?>
    </div>

  <hr>
<?php
  // @TODO: optimization and minimization
  $node   = Yii::app()->getNode();
  $nodeId = $node->id_node;
  $parent = $node->parent()->find();
  $items  = array();

  if ($node->isRoot()){
      $nodes = $node->children()->findAll();
      foreach ($nodes as $node)
          $items[] = array('label'=>$node->title, 'url'=>$node->path);
  } elseif ($parent->isRoot()){
      $nodes = $parent->children()->findAll();
      foreach ($nodes as $node)
          $items[] = array('label'=>$node->title, 'url'=>$node->path, 'active' => $node->id_node == $nodeId);
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

  <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => $this->breadcrumbs
    ));
  ?>

  <div class="row-fluid">
  <?php if (!empty($this->title)): ?>
    <h1><?php echo $this->title; ?></h1>
  <?php endif; ?>

  <?php if (!empty($this->actions)):?>
    <div style="margin-bottom: 15px;">
        <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'toggle' => 'radio',
                'buttons' => $this->actions
            ));
        ?>
    </div>
  <?php endif; ?>

    <div class="span7">
      <?php echo $content; ?>
    </div>
    <div class="span5">
  </div>

  <hr>

  <div class="footer">
    <p>&copy; <?php echo date("Y");?>, <?php echo Block::getValue('Footer: copyright', 'string', '{copyright}')?></p>
  </div>

</div>
<?php $this->renderPartial('//layouts/__footer'); ?>