<?php
    $baseUrl = Yii::app()->request->baseUrl;
?>
<?php $this->renderPartial('//layouts/__header'); ?>
<div class="container-narrow">

    <div class="masthead">
<?php $this->widget('zii.widgets.CMenu',array(
          'id'=>null,
          'items'=>array(
            array('label'=>Yii::t('site', 'Administration'), 'url'=>array('/admin')),
          ),
          'htmlOptions' => array(
              'class' => 'nav nav-pills pull-left',
          )
        ));
      ?>

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

  <div class="header">
    <h1>YiiNode</h1>
    <p class="lead">Yii framework based CMS with tree structure.</p>
    <ul class="header-links">
      <li><a href="http://github.com/xifrin/YiiNode">GitHub Project</a></li>
      <li>Version <?php echo Yii::app()->params['version'];?></li>
    <ul>
  </div>

<br>

<?php $this->renderPartial('//layouts/__menu'); ?>

  <div class="row-fluid">
    <div class="span7">
      <?php echo $content; ?>
    </div>
    <div class="span5">
      <h3>Last articles</h3>

      <?php
        Yii::import('application.modules.articles.models.*');
        $articles = Article::model()->findAll();
        foreach($articles as $article):
          $url = Yii::app()->createUrl('/articles/default/view', array('id'=>$article->id_article, 'nodeId'=>$article->id_node));
      ?>
        <a href="<?php echo $url ?>"><?php echo CHtml::encode($article->title)?></a><br>
        <small><i class="icon icon-calendar"></i> Pudlish date: <?php echo date("d.m.Y", $article->time_published); ?></small><br>
        <?php echo $article->notice; ?>

        <hr>

      <?php endforeach ;?>
    </div>

  </div>

  <hr>

  <div class="footer">
    <p>&copy; <?php echo date("Y");?>, <?php echo Block::getValue('Footer: copyright', 'string', '{copyright}')?></p>
  </div>

</div>
<?php $this->renderPartial('//layouts/__footer'); ?>