<?php
/**
 * Основной контроллер приложения
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class SiteController extends Controller
{
	/**
	 * Вывод главной страницы [действие по умолчанию]
	 */
	public function actionIndex()
	{
		$this->render('index');
	}
}

?>