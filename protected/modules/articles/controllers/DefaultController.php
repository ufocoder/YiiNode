<?php

class DefaultController extends Controller
{
    /**
     * View articles
     */
    public function actionIndex()
    {
        $this->render('/index', array(
            'id_article_tag' => null,
            'tags' => ArticleTag::model()->published()->node()->findAll(),
            'dataProvider' => Article::model()->items(),
        ));
    }

    /**
     * View articles by tag
     */
    public function actionTag($id)
    {
        $this->render('/index', array(
            'id_article_tag' => $id,
            'tags' => ArticleTag::model()->published()->node()->findAll(),
            'dataProvider' => Article::model()->items($id),
        ));
    }

    /**
     * Rss feed
     */
    public function actionRss()
    {
        $rss    = Yii::app()->getNodeSetting(Yii::app()->getNodeId(), 'rss', false);
        $pager  = Yii::app()->getNodeSetting(Yii::app()->getNodeId(), 'pager', ArticleSetting::values('pager', 'default'));

        if (!$rss)
            return false;

        $articles = Article::model()->node()->preview()->published()->findAll(array('limit'=>$pager));

        if (!$articles)
            return false;

        Yii::import('ext.feed.*');

        $node = Yii::app()->getNode();
        $host = $_SERVER['HTTP_HOST'];

        $feed = new EFeed();
        $feed->title = $node->title;
        $feed->description = $node->description;

        $feed->addChannelTag('language', 'ru-ru');
        $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
        $feed->addChannelTag('link', 'http://'.$host.rtrim($node->path, "/").'/rss' );

        foreach($articles as $article)
        {
            $item = $feed->createNewItem();
            $item->title = $article->title;
            $item->link = Yii::app()->createAbsoluteUrl('/articles/default/view', array('id'=>$article->id_article, 'nodeId'=>$node->id_node));;
            $item->date = $article->time_published;
            $item->description = $article->notice;
            $feed->addItem($item);
        }

        $feed->generateFeed();

        Yii::app()->end();
    }

    /**
     * View article
     */
    public function actionView($id = null, $slug = null)
    {
        $model = null;

        if (!empty($id))
            $model = Article::model()->node()->published()->findByPk($id);
        elseif(!empty($slug))
            $model = Article::model()->node()->published()->findByAttributes(array('slug'=>$slug));

        if ($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));

        $this->render('/view',array(
            'model'=>$model
        ));
    }

}
